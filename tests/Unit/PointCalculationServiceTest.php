<?php

namespace Tests\Unit;

use App\Models\WasteCategory;
use App\Models\WasteGrade;
use App\Services\PointCalculationService;
use PHPUnit\Framework\TestCase;

class PointCalculationServiceTest extends TestCase
{
    private PointCalculationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PointCalculationService();
    }

    public function test_botol_base_points_calculation(): void
    {
        $botol = new WasteCategory([
            'name' => 'Botol',
            'unit_type' => 'pcs',
            'unit_divisor' => 1,
            'base_point' => 1,
        ]);

        $this->assertEquals(1, $this->service->calculateBasePoints($botol, 1));
        $this->assertEquals(15, $this->service->calculateBasePoints($botol, 15));
        $this->assertEquals(0, $this->service->calculateBasePoints($botol, 0));
    }

    public function test_kaleng_base_points_calculation(): void
    {
        $kaleng = new WasteCategory([
            'name' => 'Kaleng',
            'unit_type' => 'gram',
            'unit_divisor' => 100,
            'base_point' => 1,
        ]);

        // 100 gram = 1 poin
        $this->assertEquals(1, $this->service->calculateBasePoints($kaleng, 100));
        // 250 gram = 2 poin
        $this->assertEquals(2, $this->service->calculateBasePoints($kaleng, 250));
        // 99 gram = 0 poin
        $this->assertEquals(0, $this->service->calculateBasePoints($kaleng, 99));
        // 500 gram = 5 poin
        $this->assertEquals(5, $this->service->calculateBasePoints($kaleng, 500));
    }

    public function test_grade_bonus_points(): void
    {
        $gradeA = new WasteGrade(['grade_code' => 'A', 'bonus_point' => 4]);
        $gradeB = new WasteGrade(['grade_code' => 'B', 'bonus_point' => 3]);
        $gradeC = new WasteGrade(['grade_code' => 'C', 'bonus_point' => 2]);
        $gradeD = new WasteGrade(['grade_code' => 'D', 'bonus_point' => 1]);

        $this->assertEquals(4, $this->service->calculateGradePoints($gradeA));
        $this->assertEquals(3, $this->service->calculateGradePoints($gradeB));
        $this->assertEquals(2, $this->service->calculateGradePoints($gradeC));
        $this->assertEquals(1, $this->service->calculateGradePoints($gradeD));
        $this->assertEquals(0, $this->service->calculateGradePoints(null));
    }

    public function test_total_transaction_points(): void
    {
        $botol = new WasteCategory(['name' => 'Botol', 'unit_type' => 'pcs', 'unit_divisor' => 1, 'base_point' => 1]);
        $gradeA = new WasteGrade(['grade_code' => 'A', 'bonus_point' => 4]);

        // 10 botol + Grade A (4) = 14 poin
        $this->assertEquals(14, $this->service->calculateTotalTransactionPoints($botol, 10, $gradeA));

        $kaleng = new WasteCategory(['name' => 'Kaleng', 'unit_type' => 'gram', 'unit_divisor' => 100, 'base_point' => 1]);
        $gradeC = new WasteGrade(['grade_code' => 'C', 'bonus_point' => 2]);

        // 350 gram kaleng (3 poin) + Grade C (2 poin) = 5 poin
        $this->assertEquals(5, $this->service->calculateTotalTransactionPoints($kaleng, 350, $gradeC));
    }

    public function test_greedy_rupiah_conversion(): void
    {
        // 20 Poin = Rp 1.000
        $this->assertEquals(1000, $this->service->convertPointsToRupiah(20));

        // 10 Poin = Rp 500
        $this->assertEquals(500, $this->service->convertPointsToRupiah(10));

        // 1 Poin = Rp 100
        $this->assertEquals(100, $this->service->convertPointsToRupiah(1));

        // 25 Poin: 1 x 20 (1000) + 0 x 10 + 5 x 1 (500) = 1500
        $this->assertEquals(1500, $this->service->convertPointsToRupiah(25));

        // 35 Poin: 1 x 20 (1000) + 1 x 10 (500) + 5 x 1 (500) = 2000
        $this->assertEquals(2000, $this->service->convertPointsToRupiah(35));

        // 57 Poin: 2 x 20 (2000) + 1 x 10 (500) + 7 x 1 (700) = 3200
        $this->assertEquals(3200, $this->service->convertPointsToRupiah(57));

        // 0 Poin = Rp 0
        $this->assertEquals(0, $this->service->convertPointsToRupiah(0));
    }
}
