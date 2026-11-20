<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use App\Models\WasteTransaction;
use App\Services\PointCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    public function index(): View
    {
        $user = Auth::user();

        // Riwayat transaksi terbaru
        $recentTransactions = WasteTransaction::with(['category', 'grade'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Statistik peserta
        $totalTransactionsCount = WasteTransaction::where('user_id', $user->id)->count();
        $approvedCount = WasteTransaction::where('user_id', $user->id)->where('status', 'approved')->count();
        $pendingCount = WasteTransaction::where('user_id', $user->id)->where('status', 'pending')->count();
        $rejectedCount = WasteTransaction::where('user_id', $user->id)->where('status', 'rejected')->count();

        // Total poin yang pernah ditukarkan
        $totalRedeemedPoints = PointRedemption::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('points_used');

        // Total Rupiah yang pernah dicairkan
        $totalRupiahRedeemed = PointRedemption::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('rupiah_amount');

        // Estimasi nilai Rupiah dari saldo poin saat ini menggunakan PointCalculationService
        $currentEstimatedRupiah = $this->pointService->convertPointsToRupiah($user->total_points);

        return view('peserta.dashboard', compact(
            'user',
            'recentTransactions',
            'totalTransactionsCount',
            'approvedCount',
            'pendingCount',
            'rejectedCount',
            'totalRedeemedPoints',
            'totalRupiahRedeemed',
            'currentEstimatedRupiah'
        ));
    }
}
