<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * OrderService
 *
 * Bertanggung jawab membuat record Order dan OrderItem dalam satu transaksi
 * database (atomic), sehingga tidak ada order "setengah jadi" jika terjadi error.
 */
class OrderService
{
    /**
     * Membuat Order baru beserta semua OrderItem-nya dari data cart.
     *
     * Harga dikunci (snapshot) dari session cart pada saat method ini dipanggil.
     * Sehingga perubahan harga produk di kemudian hari tidak memengaruhi
     * riwayat transaksi yang sudah ada.
     *
     * @param  array $validatedData  Data tervalidasi dari form checkout
     * @param  array $cart           Isi session keranjang belanja
     * @return Order                 Record order yang sudah tersimpan
     *
     * @throws \Throwable            Jika transaksi DB gagal
     */
    public function createFromCart(array $validatedData, array $cart): Order
    {
        return DB::transaction(function () use ($validatedData, $cart) {

            // 1. Hitung total harga dari cart (jangan percaya input dari form)
            $totalPrice = collect($cart)->sum(
                fn($item) => $item['price'] * $item['quantity']
            );

            // 2. Buat record Order utama
            $order = Order::create([
                'order_code'       => $this->generateOrderCode(),
                'customer_name'    => $validatedData['customer_name'],
                'customer_phone'   => $validatedData['customer_phone'],
                'order_type'       => $validatedData['order_type'],
                'shipping_address' => $validatedData['order_type'] === 'delivery'
                                      ? $validatedData['shipping_address']
                                      : null,
                'total_price'      => $totalPrice,
                'payment_status'   => Order::PAYMENT_PENDING,
                'order_status'     => Order::STATUS_MENUNGGU_PEMBAYARAN,
                'payment_token'    => null, // akan diupdate setelah dapat token dari Midtrans
                'whatsapp_sent'    => false,
            ]);

            // 3. Simpan setiap item keranjang sebagai OrderItem
            //    KUNCI BISNIS: harga disalin dari session cart (bukan query ulang ke products)
            $orderItems = array_values(array_map(fn($item) => [
                'order_id'   => $order->id,
                'product_id' => $item['id'],    // bisa null jika produk sudah dihapus
                'quantity'   => (int) $item['quantity'],
                'price'      => (int) $item['price'], // ← harga terkunci di sini
                'notes'      => $item['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $cart));

            OrderItem::insert($orderItems); // Batch insert untuk efisiensi

            return $order;
        });
    }

    /**
     * Membuat kode order yang unik dengan format: INV-YYYYMMDD-XXXX
     * Contoh: INV-20240715-A3F9
     */
    private function generateOrderCode(): string
    {
        do {
            $code = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
