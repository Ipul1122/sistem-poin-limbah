<?php

namespace Database\Seeders;

use App\Models\WasteCategory;
use Illuminate\Database\Seeder;

class WasteCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // STRICT: Hanya dua kategori limbah yang diizinkan (Botol & Kaleng)
        $categories = [
            [
                'name' => 'Botol',
                'unit_type' => 'pcs',
                'unit_divisor' => 1,
                'base_point' => 1,
            ],
            [
                'name' => 'Kaleng',
                'unit_type' => 'gram',
                'unit_divisor' => 100,
                'base_point' => 1,
            ],
        ];

        foreach ($categories as $cat) {
            WasteCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
