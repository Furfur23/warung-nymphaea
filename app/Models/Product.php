<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
    ];

    /**
     * Cast tipe data otomatis.
     */
    protected $casts = [
        'is_available' => 'boolean',
        'price'        => 'integer',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Setiap produk dimiliki oleh satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Satu produk bisa muncul di banyak baris order_items (lintas transaksi).
     * Catatan: Relasi ini ke data historis. Harga aktual per transaksi
     * tersimpan di kolom `price` pada tabel order_items, bukan di sini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // =========================================================================
    // ACCESSOR / HELPER
    // =========================================================================

    /**
     * Menampilkan harga dalam format Rupiah yang mudah dibaca.
     * Contoh penggunaan di Blade: {{ $product->formatted_price }}
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
