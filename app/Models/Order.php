<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'order_type',
        'shipping_address',
        'total_price',
        'payment_status',
        'order_status',
        'payment_token',
        'whatsapp_sent',
    ];

    /**
     * Cast tipe data otomatis.
     */
    protected $casts = [
        'total_price'    => 'integer',
        'whatsapp_sent'  => 'boolean',
    ];

    // =========================================================================
    // KONSTANTA STATUS
    // Mendefinisikan nilai enum sebagai konstanta agar tidak ada "magic string"
    // yang tersebar di seluruh kode (typo-safe & mudah di-refactor).
    // =========================================================================

    // Payment Status
    const PAYMENT_PENDING    = 'pending';
    const PAYMENT_SETTLEMENT = 'settlement';
    const PAYMENT_EXPIRE     = 'expire';
    const PAYMENT_CANCEL     = 'cancel';

    // Order Status
    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    const STATUS_DIPROSES            = 'diproses';
    const STATUS_SIAP_DIAMBIL        = 'siap_diambil';
    const STATUS_DALAM_PERJALANAN    = 'dalam_perjalanan';
    const STATUS_SELESAI             = 'selesai';

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Satu pesanan memiliki banyak item (detail produk yang dipesan).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // =========================================================================
    // ACCESSOR / HELPER
    // =========================================================================

    /**
     * Menampilkan total harga dalam format Rupiah.
     * Contoh: {{ $order->formatted_total }}
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Cek apakah pembayaran sudah lunas (settlement).
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_SETTLEMENT;
    }

    // =========================================================================
    // SCOPE QUERY
    // Mempermudah filtering data di controller/service.
    // =========================================================================

    /**
     * Scope untuk pesanan yang menunggu pembayaran.
     * Contoh: Order::pending()->get()
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    /**
     * Scope untuk pesanan yang sudah lunas.
     * Contoh: Order::paid()->get()
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_SETTLEMENT);
    }

    /**
     * Scope untuk pesanan yang sedang aktif diproses.
     * Contoh: Order::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->whereIn('order_status', [
            self::STATUS_DIPROSES,
            self::STATUS_SIAP_DIAMBIL,
            self::STATUS_DALAM_PERJALANAN,
        ]);
    }
}
