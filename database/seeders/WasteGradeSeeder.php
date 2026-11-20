<?php

namespace Database\Seeders;

use App\Models\WasteGrade;
use Illuminate\Database\Seeder;

class WasteGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'grade_code' => 'A',
                'label' => 'Sangat Bagus',
                'bonus_point' => 4,
            ],
            [
                'grade_code' => 'B',
                'label' => 'Bagus',
                'bonus_point' => 3,
            ],
            [
                'grade_code' => 'C',
                'label' => 'Cukup',
                'bonus_point' => 2,
            ],
            [
                'grade_code' => 'D',
                'label' => 'Kurang Layak',
                'bonus_point' => 1,
            ],
        ];

        foreach ($grades as $grade) {
            WasteGrade::updateOrCreate(['grade_code' => $grade['grade_code']], $grade);
        }
    }
}
