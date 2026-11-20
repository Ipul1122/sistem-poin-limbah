<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use App\Models\User;
use App\Models\WasteTransaction;
use App\Services\PointCalculationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    public function index(): View
    {
        // Statistik Peserta
        $totalPeserta = User::where('role', 'peserta')->count();

        // Statistik Transaksi Limbah
        $totalTransactions = WasteTransaction::count();
        $pendingTransactionsCount = WasteTransaction::where('status', 'pending')->count();
        $approvedTransactionsCount = WasteTransaction::where('status', 'approved')->count();
        $rejectedTransactionsCount = WasteTransaction::where('status', 'rejected')->count();

        // Poin Beredar & Poin Tersalurkan
        $totalActivePoints = User::where('role', 'peserta')->sum('total_points');
        $totalApprovedPoints = WasteTransaction::where('status', 'approved')->sum('earned_points');

        // Statistik Klaim Penukaran Poin
        $pendingRedemptionsCount = PointRedemption::where('status', 'pending')->count();
        $pendingRedemptionRupiah = PointRedemption::where('status', 'pending')->sum('rupiah_amount');

        $completedRedemptionsCount = PointRedemption::where('status', 'completed')->count();
        $completedRedemptionRupiah = PointRedemption::where('status', 'completed')->sum('rupiah_amount');

        // Transaksi Pending yang butuh diverifikasi segera
        $pendingTransactions = WasteTransaction::with(['user', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Klaim Penukaran Pending terbaru
        $pendingRedemptions = PointRedemption::with('user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalTransactions',
            'pendingTransactionsCount',
            'approvedTransactionsCount',
            'rejectedTransactionsCount',
            'totalActivePoints',
            'totalApprovedPoints',
            'pendingRedemptionsCount',
            'pendingRedemptionRupiah',
            'completedRedemptionsCount',
            'completedRedemptionRupiah',
            'pendingTransactions',
            'pendingRedemptions'
        ));
    }
}
