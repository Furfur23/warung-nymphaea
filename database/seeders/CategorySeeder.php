<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Mengisi tabel categories dengan data awal.
     */
    public function run(): void
    {
        $categories = [
            'Makanan Utama',
            'Lauk & Pelengkap',
            'Minuman',
            'Cemilan & Snack',
            'Paket Hemat',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        $this->command->info('✅ Kategori berhasil di-seed (' . count($categories) . ' data).');
    }
}
