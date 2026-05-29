<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'notes',
    ];

    /**
     * Cast tipe data otomatis.
     */
    protected $casts = [
        'quantity' => 'integer',
        'price'    => 'integer',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Setiap item bagian dari satu pesanan.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Setiap item merujuk ke satu produk di katalog.
     *
     * PENTING: Relasi ini bisa bernilai NULL jika produk telah dihapus dari
     * katalog. Selalu gunakan null-safe operator (?->) saat mengaksesnya.
     * Contoh: $item->product?->name
     *
     * Harga AKTUAL transaksi tetap aman tersimpan di $item->price.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name'  => '[Produk Telah Dihapus]',
            'image' => null,
        ]);
    }

    // =========================================================================
    // ACCESSOR / HELPER
    // =========================================================================

    /**
     * Menghitung subtotal untuk item ini (harga terkunci × jumlah).
     * Contoh: {{ $item->formatted_subtotal }}
     */
    public function getSubtotalAttribute(): int
    {
        return $this->price * $this->quantity;
    }

    /**
     * Subtotal dalam format Rupiah.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Harga satuan terkunci dalam format Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
