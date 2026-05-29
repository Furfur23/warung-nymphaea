<?php
 
namespace App\Http\Controllers;
 
use App\Models\Order;
use App\Services\MidtransService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;
 
class CheckoutController extends Controller
{
    public function __construct(
        private readonly OrderService   $orderService,
        private readonly MidtransService $midtrans,
    ) {}
 
    // =========================================================================
    // METHOD index() — Tampilkan Form Checkout
    // =========================================================================
 
    /**
     * Menampilkan halaman form checkout.
     * Guard: redirect ke katalog jika keranjang kosong.
     */
    public function index()
    {
        $cart = session('cart', []);
 
        if (empty($cart)) {
            return redirect()->route('catalog.index')
                             ->with('error', 'Keranjang belanjamu masih kosong.');
        }
 
        $total      = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $cartCount  = collect($cart)->sum('quantity');
        $clientKey  = $this->midtrans->getClientKey();
 
        return view('checkout', compact('cart', 'total', 'cartCount', 'clientKey'));
    }
 
    // =========================================================================
    // METHOD process() — Proses Form & Buat Transaksi Midtrans
    // =========================================================================
 
    /**
     * Memproses form checkout:
     *  1. Validasi input
     *  2. Buat Order + OrderItem di DB (atomic transaction)
     *  3. Kirim ke Midtrans Snap → dapatkan snap_token
     *  4. Update payment_token di Order
     *  5. Kosongkan cart session
     *  6. Tampilkan halaman pembayaran dengan Snap Pop-up
     */
    public function process(Request $request)
    {
        // ── 1. Validasi Input Form ────────────────────────────────────────────
        $validated = $request->validate([
            'customer_name'    => ['required', 'string', 'min:2', 'max:100'],
            'customer_phone'   => ['required', 'string', 'regex:/^(\+62|62|0)[0-9]{8,13}$/'],
            'order_type'       => ['required', 'in:delivery,takeaway'],
            'shipping_address' => ['required_if:order_type,delivery', 'nullable', 'string', 'max:500'],
            'order_notes'      => ['nullable', 'string', 'max:255'],
        ], [
            'customer_name.required'    => 'Nama lengkap wajib diisi.',
            'customer_name.min'         => 'Nama minimal 2 karakter.',
            'customer_phone.required'   => 'Nomor WhatsApp wajib diisi.',
            'customer_phone.regex'      => 'Format nomor HP tidak valid. Contoh: 08123456789',
            'order_type.required'       => 'Pilih jenis pesanan (Delivery atau Takeaway).',
            'shipping_address.required_if' => 'Alamat lengkap wajib diisi untuk pesanan delivery.',
        ]);
 
        // ── 2. Guard: Keranjang Tidak Boleh Kosong ────────────────────────────
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('catalog.index')
                             ->with('error', 'Sesi keranjangmu telah habis. Silakan tambahkan produk lagi.');
        }
 
        try {
            // ── 3. Buat Order & OrderItem di Database ─────────────────────────
            $order = $this->orderService->createFromCart($validated, $cart);
 
            // ── 4. Kirim Request ke Midtrans Snap API ─────────────────────────
            $snapData = $this->midtrans->createSnapTransaction($order, $cart);
 
            // ── 5. Update payment_token di tabel orders ───────────────────────
            $order->update(['payment_token' => $snapData['snap_token']]);
 
            // ── 6. Kosongkan session keranjang ────────────────────────────────
            session()->forget('cart');
 
            // ── 7. Render halaman pembayaran (Snap Pop-up) ────────────────────
            return view('payment', [
                'order'       => $order,
                'snapToken'   => $snapData['snap_token'],
                'redirectUrl' => $snapData['redirect_url'],
                'snapJsUrl'   => $this->midtrans->getSnapJsUrl(),
                'clientKey'   => $this->midtrans->getClientKey(),
            ]);
 
        } catch (RuntimeException $e) {
            // Error dari Midtrans API (token tidak didapat, dll.)
            Log::warning('[Checkout] Midtrans API error', [
                'error'        => $e->getMessage(),
                'customer'     => $validated['customer_name'],
                'order_exists' => isset($order),
            ]);
 
            // Jika order sudah terbuat tapi token gagal, tandai sebagai cancel
            if (isset($order)) {
                $order->update(['payment_status' => Order::PAYMENT_CANCEL]);
            }
 
            return redirect()->route('checkout.index')
                             ->withInput()
                             ->with('error', 'Gagal menghubungi payment gateway. Silakan coba beberapa saat lagi. (' . $e->getMessage() . ')');
 
        } catch (Throwable $e) {
            // Error tak terduga (DB, network, dll.)
            Log::error('[Checkout] Unexpected error during checkout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
 
            return redirect()->route('checkout.index')
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan sistem. Tim kami sudah diberitahu. Silakan coba lagi.');
        }
    }
 
    // =========================================================================
    // METHOD finish() — Callback setelah Pembayaran Selesai di Snap
    // =========================================================================
 
    /**
     * Halaman yang ditampilkan Midtrans setelah pembeli menyelesaikan/menutup
     * halaman Snap. Ini BUKAN konfirmasi pembayaran (itu tugas webhook).
     * Hanya menampilkan status sementara berdasarkan query param dari Midtrans.
     */
    public function finish(Request $request)
    {
        $orderCode     = $request->query('order_id');
        $statusCode    = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');
 
        // Eager-load items + product agar tersedia di view untuk:
        // 1. Menampilkan daftar item pesanan
        // 2. Menyusun teks otomatis tombol WhatsApp
        $order = $orderCode
            ? Order::with(['items.product'])
                   ->where('order_code', $orderCode)
                   ->first()
            : null;
 
        // Status final ditentukan oleh webhook, bukan dari redirect ini
        return view('finish', compact('order', 'statusCode', 'transactionStatus'));
    }
}