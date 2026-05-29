# 🍜 Warung Kami — Panduan Integrasi Midtrans

## Arsitektur Sistem Checkout

```
Browser                Laravel                 Midtrans
  │                       │                       │
  │── POST /checkout/process ──▶                  │
  │                       │── POST Snap API ──────▶│
  │                       │◀── snap_token ─────────│
  │                       │  (simpan ke DB)        │
  │◀── render payment.blade.php ──                 │
  │  (snap_token disisipkan di JS)                 │
  │                       │                       │
  │── snap.pay(token) ────────────────────────────▶│
  │◀─────────── Snap Pop-up muncul ────────────────│
  │  [User bayar via QRIS/GoPay/dll]               │
  │◀────── onSuccess callback ─────────────────────│
  │── GET /checkout/finish?... ──▶                 │
  │                       │                       │
  │                       │◀── POST /webhook/midtrans (async)
  │                       │  (konfirmasi FINAL)    │
  │                       │── update DB status     │
  │                       │── kirim WA notif       │
```

---

## Langkah 1 — Tidak Perlu Install SDK

Proyek ini menggunakan **Laravel HTTP Client** (bawaan Laravel, berbasis Guzzle)
untuk berkomunikasi langsung dengan Midtrans REST API.

Tidak perlu `composer require midtrans/midtrans-php`.

---

## Langkah 2 — Setup Environment

Salin konfigurasi dari `.env.midtrans.example` ke `.env`:

```bash
# Tambahkan ke .env
MIDTRANS_MERCHANT_ID=G123456789
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxxxxx
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

Lalu clear config cache:
```bash
php artisan config:clear
```

---

## Langkah 3 — Daftarkan Webhook URL di Dashboard Midtrans

1. Login ke [Sandbox Dashboard](https://dashboard.sandbox.midtrans.com)
2. Buka **Settings → Configuration**
3. Isi **Payment Notification URL** dengan:
   - Lokal (pakai ngrok): `https://xxxx.ngrok.io/webhook/midtrans`
   - Production: `https://domain-kamu.com/webhook/midtrans`
4. Klik **Save**

### Untuk lokal development dengan ngrok:
```bash
# Install ngrok: https://ngrok.com
ngrok http 8000

# Salin URL ngrok (contoh: https://abc123.ngrok.io)
# Masukkan ke Midtrans Dashboard sebagai Notification URL
```

---

## Langkah 4 — Struktur File yang Dihasilkan

```
app/
├── Http/Controllers/
│   ├── ProductController.php   # Katalog produk
│   ├── CartController.php      # Keranjang belanja (session)
│   └── CheckoutController.php  # Checkout + integrasi Midtrans
├── Services/
│   ├── MidtransService.php     # HTTP client ke Midtrans Snap API
│   └── OrderService.php        # Buat Order + OrderItem (DB transaction)
└── Models/
    ├── Category.php
    ├── Product.php
    ├── Order.php
    └── OrderItem.php

config/
└── midtrans.php                # Konfigurasi Midtrans

resources/views/
├── catalog.blade.php           # Halaman katalog menu
├── cart.blade.php              # Halaman keranjang belanja
├── checkout.blade.php          # Form data pengiriman
├── payment.blade.php           # Snap pop-up pembayaran
└── finish.blade.php            # Halaman sesudah bayar

routes/
└── web.php                     # Semua route
```

---

## Langkah 5 — Route Summary

| Method | URL | Name | Keterangan |
|--------|-----|------|-----------|
| GET | `/` | `catalog.index` | Katalog produk |
| GET | `/cart` | `cart.index` | Keranjang |
| POST | `/cart/add/{id}` | `cart.add` | Tambah ke keranjang |
| PATCH | `/cart/update/{id}` | `cart.update` | Update quantity |
| DELETE | `/cart/remove/{id}` | `cart.remove` | Hapus item |
| DELETE | `/cart/clear` | `cart.clear` | Kosongkan keranjang |
| GET | `/checkout` | `checkout.index` | Form checkout |
| POST | `/checkout/process` | `checkout.process` | Proses + buat transaksi |
| GET | `/checkout/finish` | `checkout.finish` | Halaman sesudah bayar |
| POST | `/webhook/midtrans` | `webhook.midtrans` | *(Iterasi berikutnya)* |

---

## Langkah 6 — Alur Pembayaran Detail

### `CheckoutController::process()` melakukan ini secara berurutan:

1. **Validasi form** — nama, nomor WA, jenis pesanan, alamat
2. **Guard** — pastikan session cart tidak kosong
3. **`OrderService::createFromCart()`** — atomic DB transaction:
   - Hitung total dari session cart (bukan dari input user)
   - Buat record `orders` dengan status `pending`
   - Batch insert semua `order_items` (harga dikunci dari session)
4. **`MidtransService::createSnapTransaction()`** — HTTP POST ke Midtrans:
   - Auth: Basic Auth dengan `server_key` sebagai username, password kosong
   - Payload: transaction_details, customer_details, item_details
   - Response: `snap_token` + `redirect_url`
5. **Update** `orders.payment_token` dengan snap_token
6. **Kosongkan** session cart
7. **Render** `payment.blade.php` dengan snap_token disisipkan ke JS

### `payment.blade.php` di browser:

- Load `snap.js` dari CDN Midtrans dengan `data-client-key`
- Auto-open `window.snap.pay(snapToken)` setelah 800ms
- Callback `onSuccess/onPending` → redirect ke `/checkout/finish?...`
- Callback `onError` → tampilkan error, aktifkan fallback link
- Callback `onClose` → reset tombol "Bayar Lagi"

---

## Langkah 7 — Test Sandbox

Gunakan kredensial test Midtrans:

| Metode | Detail |
|--------|--------|
| **QRIS** | Otomatis success di sandbox |
| **GoPay** | Simulate success/failure di app Gojek Simulator |
| **BCA Virtual Account** | Bayar via simulator: [simulator.sandbox.midtrans.com](https://simulator.sandbox.midtrans.com) |
| **Kartu Kredit** | `4811 1111 1111 1114`, exp: `01/25`, CVV: `123` |

---

## Catatan Penting

### Mengapa tidak pakai Midtrans PHP SDK?

Laravel sudah memiliki HTTP Client berbasis Guzzle yang powerful.
Menggunakan HTTP Client langsung memberikan:
- ✅ Tidak ada dependency tambahan
- ✅ Logging terintegrasi (`Log::info/error`)
- ✅ Lebih mudah di-mock saat testing
- ✅ Timeout dan retry bisa dikontrol penuh

### Mengapa harga dikunci di `order_items.price`?

```php
// Di OrderService::createFromCart():
'price' => (int) $item['price'], // ← diambil dari SESSION, bukan query ulang ke DB
```

Jika admin mengubah `products.price` setelah transaksi terjadi,
riwayat `order_items.price` tetap mencatat harga SAAT transaksi.
Ini memastikan laporan keuangan akurat.

### Webhook vs Redirect Finish

- **`/checkout/finish`** (redirect) — hanya untuk UX. Status dari query params
  bisa dimanipulasi. JANGAN gunakan ini sebagai konfirmasi final.
- **`/webhook/midtrans`** (POST dari server Midtrans) — ini yang menentukan
  status pembayaran final. Diimplementasi di iterasi berikutnya.
