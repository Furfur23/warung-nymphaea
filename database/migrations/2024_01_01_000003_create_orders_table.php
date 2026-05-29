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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Kode unik untuk invoice, contoh: INV-20240715-A3F9
            $table->string('order_code')->unique();

            // Data Pelanggan (Guest Checkout - tanpa relasi ke tabel users)
            $table->string('customer_name');
            $table->string('customer_phone'); // Nomor WhatsApp

            // Jenis & Detail Pengiriman
            $table->enum('order_type', ['delivery', 'takeaway']);
            $table->text('shipping_address')->nullable(); // Wajib diisi jika order_type = 'delivery'

            // Keuangan
            $table->unsignedInteger('total_price'); // Total harga dalam Rupiah

            // Status Pembayaran dari Payment Gateway
            $table->enum('payment_status', ['pending', 'settlement', 'expire', 'cancel'])
                  ->default('pending');

            // Status Operasional Pesanan
            $table->enum('order_status', [
                'menunggu_pembayaran',
                'diproses',
                'siap_diambil',
                'dalam_perjalanan',
                'selesai',
            ])->default('menunggu_pembayaran');

            // Integrasi Payment Gateway
            $table->string('payment_token')->nullable(); // Snap Token dari Midtrans / Token Xendit

            // Integrasi Notifikasi WhatsApp
            $table->boolean('whatsapp_sent')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
