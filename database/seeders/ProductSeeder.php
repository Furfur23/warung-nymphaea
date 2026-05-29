<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Mengisi tabel products dengan data contoh menu.
     */
    public function run(): void
    {
        // Ambil ID kategori sekali saja untuk efisiensi query
        $makananUtama  = Category::where('slug', 'makanan-utama')->value('id');
        $laukPelengkap = Category::where('slug', 'lauk-pelengkap')->value('id');
        $minuman       = Category::where('slug', 'minuman')->value('id');
        $cemilan       = Category::where('slug', 'cemilan-snack')->value('id');
        $paketHemat    = Category::where('slug', 'paket-hemat')->value('id');

        $products = [

            // ----------------------------------------------------------------
            // Makanan Utama
            // ----------------------------------------------------------------
            [
                'category_id'  => $makananUtama,
                'name'         => 'Nasi Goreng Spesial',
                'description'  => 'Nasi goreng dengan telur, ayam suwir, dan bumbu rahasia khas dapur kami.',
                'price'        => 18000,
                'is_available' => true,
            ],
            [
                'category_id'  => $makananUtama,
                'name'         => 'Mie Ayam Bakso',
                'description'  => 'Mie kenyal dengan topping ayam cincang, bakso sapi, dan kuah kaldu gurih.',
                'price'        => 15000,
                'is_available' => true,
            ],
            [
                'category_id'  => $makananUtama,
                'name'         => 'Nasi Uduk Komplit',
                'description'  => 'Nasi uduk harum dengan lauk ayam goreng, tempe orek, dan sambal kacang.',
                'price'        => 20000,
                'is_available' => true,
            ],

            // ----------------------------------------------------------------
            // Lauk & Pelengkap
            // ----------------------------------------------------------------
            [
                'category_id'  => $laukPelengkap,
                'name'         => 'Ayam Goreng Kremes',
                'description'  => 'Ayam goreng renyah dengan taburan kremes gurih.',
                'price'        => 12000,
                'is_available' => true,
            ],
            [
                'category_id'  => $laukPelengkap,
                'name'         => 'Tempe Mendoan',
                'description'  => 'Tempe tipis berbumbu, digoreng setengah matang, disajikan dengan kecap pedas.',
                'price'        => 5000,
                'is_available' => true,
            ],
            [
                'category_id'  => $laukPelengkap,
                'name'         => 'Telur Dadar',
                'description'  => 'Telur dadar tipis dengan irisan daun bawang.',
                'price'        => 5000,
                'is_available' => true,
            ],

            // ----------------------------------------------------------------
            // Minuman
            // ----------------------------------------------------------------
            [
                'category_id'  => $minuman,
                'name'         => 'Es Teh Manis',
                'description'  => 'Teh manis dingin menyegarkan.',
                'price'        => 5000,
                'is_available' => true,
            ],
            [
                'category_id'  => $minuman,
                'name'         => 'Es Jeruk Peras',
                'description'  => 'Jeruk peras segar dicampur sirup gula dan es batu.',
                'price'        => 8000,
                'is_available' => true,
            ],
            [
                'category_id'  => $minuman,
                'name'         => 'Jus Alpukat',
                'description'  => 'Jus alpukat kental dengan cokelat kental manis.',
                'price'        => 12000,
                'is_available' => true,
            ],

            // ----------------------------------------------------------------
            // Cemilan & Snack
            // ----------------------------------------------------------------
            [
                'category_id'  => $cemilan,
                'name'         => 'Pisang Goreng Crispy',
                'description'  => 'Pisang kepok goreng tepung renyah, tersedia topping cokelat & keju.',
                'price'        => 10000,
                'is_available' => true,
            ],
            [
                'category_id'  => $cemilan,
                'name'         => 'Tahu Bulat Goreng',
                'description'  => 'Tahu bulat digoreng kering, gurih dan renyah di luar.',
                'price'        => 7000,
                'is_available' => true,
            ],

            // ----------------------------------------------------------------
            // Paket Hemat
            // ----------------------------------------------------------------
            [
                'category_id'  => $paketHemat,
                'name'         => 'Paket Nasi + Lauk + Minum',
                'description'  => 'Nasi putih + 1 lauk pilihan + 1 minuman pilihan. Lebih hemat!',
                'price'        => 25000,
                'is_available' => true,
            ],
            [
                'category_id'  => $paketHemat,
                'name'         => 'Paket Keluarga (4 Porsi)',
                'description'  => '4 nasi goreng spesial + 4 es teh manis. Cocok untuk makan bersama.',
                'price'        => 85000,
                'is_available' => false, // Contoh produk yang sedang tidak tersedia
            ],
        ];

        foreach ($products as $data) {
            $data['slug'] = Str::slug($data['name']);

            Product::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('✅ Produk berhasil di-seed (' . count($products) . ' data).');
    }
}
