<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Jika order dihapus, semua item-nya ikut terhapus (CASCADE)
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            // Jika produk dihapus dari katalog, item historis tetap ada tapi kolom ini jadi NULL
            // Harga & nama sudah terkunci di kolom 'price' di bawah, jadi data historis aman
            $table->foreignId('product_id')
                  ->nullable()
                  ->constrained('products')
                  ->nullOnDelete();

            $table->unsignedInteger('quantity');

            /**
             * KUNCI BISNIS: Harga produk dikunci saat transaksi terjadi.
             * Kolom ini menyimpan snapshot harga, BUKAN relasi ke products.price.
             * Sehingga riwayat keuangan tidak akan berubah meski admin mengubah
             * harga produk di kemudian hari.
             */
            $table->unsignedInteger('price');

            $table->string('notes')->nullable(); // Catatan pembeli untuk item ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
