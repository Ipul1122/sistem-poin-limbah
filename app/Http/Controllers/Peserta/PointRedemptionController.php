<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use App\Services\PointCalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PointRedemptionController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    /**
     * Tampilkan halaman penukaran poin dan riwayat klaim penukaran ke Rupiah
     */
    public function index(): View
    {
        $user = Auth::user();

        $redemptions = PointRedemption::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $breakdown = $this->pointService->calculateRedemptionBreakdown($user->total_points);

        return view('peserta.redemptions.index', compact('user', 'redemptions', 'breakdown'));
    }

    /**
     * API Preview kalkulasi greedy penukaran poin ke Rupiah secara dinamis
     */
    public function preview(Request $request): JsonResponse
    {
        $points = max(0, (int) $request->query('points', 0));
        $breakdown = $this->pointService->calculateRedemptionBreakdown($points);

        return response()->json($breakdown);
    }

    /**
     * Simpan pengajuan penukaran poin ke Rupiah
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'points_used' => ['required', 'integer', 'min:1'],
            'payment_info' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'points_used.required' => 'Jumlah poin yang ingin ditukarkan wajib diisi.',
            'points_used.integer' => 'Jumlah poin harus berupa angka.',
            'points_used.min' => 'Jumlah poin minimal 1 poin.',
            'payment_info.required' => 'Informasi tujuan pencairan (Bank/E-Wallet & No. Rekening) wajib diisi.',
            'payment_info.max' => 'Informasi tujuan maksimal 255 karakter.',
        ]);

        $pointsToRedeem = (int) $validated['points_used'];

        // STRICT: Peserta tidak dapat menukarkan poin melebihi total_points yang dimilikinya saat ini
        if ($pointsToRedeem > $user->total_points) {
            throw ValidationException::withMessages([
                'points_used' => "Saldo poin Anda tidak mencukupi. Anda saat ini memiliki {$user->total_points} poin.",
            ]);
        }

        // Kalkulasi nilai Rupiah via PointCalculationService dengan formula Greedy
        $rupiahAmount = $this->pointService->convertPointsToRupiah($pointsToRedeem);

        // Eksekusi transaksi DB
        DB::transaction(function () use ($user, $pointsToRedeem, $rupiahAmount, $validated) {
            // Potong saldo poin peserta secara aman
            $user->decrement('total_points', $pointsToRedeem);

            // Buat record klaim penukaran dengan status 'pending'
            PointRedemption::create([
                'user_id' => $user->id,
                'points_used' => $pointsToRedeem,
                'rupiah_amount' => $rupiahAmount,
                'status' => 'pending',
                'payment_info' => $validated['payment_info'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        $formattedRupiah = $this->pointService->formatRupiah($rupiahAmount);

        return redirect()->route('peserta.redemptions.index')
            ->with('success', "Permintaan klaim penukaran {$pointsToRedeem} poin senilai {$formattedRupiah} berhasil diajukan! Admin akan memproses pencairan dana Anda.");
    }
}
