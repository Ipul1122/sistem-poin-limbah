<?php

namespace Database\Seeders;

use App\Models\PointRedemption;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WasteGrade;
use App\Models\WasteTransaction;
use App\Services\PointCalculationService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WasteCategorySeeder::class,
            WasteGradeSeeder::class,
            UserSeeder::class,
        ]);

        $admin = User::where('role', 'admin')->first();
        $peserta = User::where('email', 'peserta@ecopoint.test')->first();
        $botol = WasteCategory::where('name', 'Botol')->first();
        $kaleng = WasteCategory::where('name', 'Kaleng')->first();
        $gradeA = WasteGrade::where('grade_code', 'A')->first();
        $gradeB = WasteGrade::where('grade_code', 'B')->first();

        $calcService = new PointCalculationService();

        // Transaksi 1: Disetujui (Botol 20 pcs + Grade A) -> 20 + 4 = 24 poin
        $pts1 = $calcService->calculateTotalTransactionPoints($botol, 20, $gradeA);
        WasteTransaction::create([
            'user_id' => $peserta->id,
            'verified_by' => $admin->id,
            'category_id' => $botol->id,
            'grade_id' => $gradeA->id,
            'quantity_or_weight' => 20,
            'earned_points' => $pts1,
            'status' => 'approved',
            'notes' => 'Botol bersih, tanpa tutup dan label.',
        ]);

        // Transaksi 2: Disetujui (Kaleng 500 gram + Grade B) -> (500/100 * 1) + 3 = 8 poin
        $pts2 = $calcService->calculateTotalTransactionPoints($kaleng, 500, $gradeB);
        WasteTransaction::create([
            'user_id' => $peserta->id,
            'verified_by' => $admin->id,
            'category_id' => $kaleng->id,
            'grade_id' => $gradeB->id,
            'quantity_or_weight' => 500,
            'earned_points' => $pts2,
            'status' => 'approved',
            'notes' => 'Kaleng bersih sudah digepengkan.',
        ]);

        // Transaksi 3: Masih Pending (Peserta mengajukan Botol 15 pcs) -> poin belum masuk saldo!
        WasteTransaction::create([
            'user_id' => $peserta->id,
            'verified_by' => null,
            'category_id' => $botol->id,
            'grade_id' => null,
            'quantity_or_weight' => 15,
            'earned_points' => 0,
            'status' => 'pending',
            'notes' => 'Setoran limbah botol plastik air mineral 600ml.',
        ]);

        // Transaksi 4: Kaleng 300 gram masih Pending
        WasteTransaction::create([
            'user_id' => $peserta->id,
            'verified_by' => null,
            'category_id' => $kaleng->id,
            'grade_id' => null,
            'quantity_or_weight' => 300,
            'earned_points' => 0,
            'status' => 'pending',
            'notes' => 'Kaleng minuman soda.',
        ]);

        // Sample Penukaran Poin (Redemption)
        // Peserta menukarkan 20 poin = Rp 1.000 (status: completed)
        PointRedemption::create([
            'user_id' => $peserta->id,
            'points_used' => 20,
            'rupiah_amount' => 1000,
            'status' => 'completed',
            'payment_info' => 'DANA - 081234567890 (a/n Budi Santoso)',
            'notes' => 'Transfer berhasil via QRIS/DANA.',
        ]);

        // Sample Penukaran Poin Pending: 10 poin = Rp 500
        PointRedemption::create([
            'user_id' => $peserta->id,
            'points_used' => 10,
            'rupiah_amount' => 500,
            'status' => 'pending',
            'payment_info' => 'GoPay - 081234567890 (a/n Budi Santoso)',
            'notes' => null,
        ]);
    }
}
