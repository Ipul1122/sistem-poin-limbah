<?php

namespace App\Services;

use App\Models\WasteCategory;
use App\Models\WasteGrade;

class PointCalculationService
{
    /**
     * Hitung Poin Dasar (Base Point) berdasarkan kategori dan jumlah/berat.
     * - Botol: 1 Botol = 1 Poin (Qty / 1 * 1)
     * - Kaleng: 100 gram = 1 Poin ((Berat Gram / 100) * 1)
     */
    public function calculateBasePoints(WasteCategory $category, int $quantityOrWeight): int
    {
        if ($quantityOrWeight <= 0) {
            return 0;
        }

        $divisor = max(1, $category->unit_divisor);
        $units = intdiv($quantityOrWeight, $divisor);

        return $units * $category->base_point;
    }

    /**
     * Hitung Poin Grade / Kondisi Kelayakan
     * Grade A = 4 Poin, B = 3 Poin, C = 2 Poin, D = 1 Poin
     */
    public function calculateGradePoints(?WasteGrade $grade): int
    {
        if (!$grade) {
            return 0;
        }

        return (int) $grade->bonus_point;
    }

    /**
     * Hitung Total Poin Transaksi = Poin Dasar + Poin Grade Kelayakan
     */
    public function calculateTotalTransactionPoints(
        WasteCategory $category,
        int $quantityOrWeight,
        ?WasteGrade $grade = null
    ): int {
        $base = $this->calculateBasePoints($category, $quantityOrWeight);
        $bonus = $this->calculateGradePoints($grade);

        return $base + $bonus;
    }

    /**
     * Konversi Penukaran Poin ke Nilai Rupiah menggunakan metode pecahan Greedy:
     * - 20 Poin = Rp 1.000
     * - 10 Poin = Rp 500
     * - 1 Poin  = Rp 100
     */
    public function convertPointsToRupiah(int $points): int
    {
        if ($points <= 0) {
            return 0;
        }

        $p20 = intdiv($points, 20);
        $rem20 = $points % 20;

        $p10 = intdiv($rem20, 10);
        $rem10 = $rem20 % 10;

        $p1 = $rem10;

        return ($p20 * 1000) + ($p10 * 500) + ($p1 * 100);
    }

    /**
     * Mendapatkan rincian pecahan greedy penukaran poin.
     */
    public function calculateRedemptionBreakdown(int $points): array
    {
        $points = max(0, $points);

        $p20 = intdiv($points, 20);
        $rem20 = $points % 20;

        $p10 = intdiv($rem20, 10);
        $rem10 = $rem20 % 10;

        $p1 = $rem10;

        $totalRupiah = ($p20 * 1000) + ($p10 * 500) + ($p1 * 100);

        return [
            'points' => $points,
            'denominations' => [
                '20' => [
                    'label' => '20 Poin (Rp 1.000)',
                    'count' => $p20,
                    'rate' => 1000,
                    'subtotal' => $p20 * 1000,
                ],
                '10' => [
                    'label' => '10 Poin (Rp 500)',
                    'count' => $p10,
                    'rate' => 500,
                    'subtotal' => $p10 * 500,
                ],
                '1' => [
                    'label' => '1 Poin (Rp 100)',
                    'count' => $p1,
                    'rate' => 100,
                    'subtotal' => $p1 * 100,
                ],
            ],
            'total_rupiah' => $totalRupiah,
            'formatted_rupiah' => $this->formatRupiah($totalRupiah),
        ];
    }

    /**
     * Format nilai integer ke format Rupiah standar.
     */
    public function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
