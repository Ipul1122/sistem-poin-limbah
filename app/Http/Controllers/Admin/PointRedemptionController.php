<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointRedemption;
use App\Services\PointCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PointRedemptionController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    /**
     * Tampilkan seluruh klaim penukaran poin ke Rupiah
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = PointRedemption::with('user');

        if (in_array($status, ['pending', 'completed', 'rejected'])) {
            $query->where('status', $status);
        }

        $redemptions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.redemptions.index', compact('redemptions', 'status'));
    }

    /**
     * Tampilkan detail klaim penukaran poin
     */
    public function show(PointRedemption $redemption): View
    {
        $redemption->load('user');
        $breakdown = $this->pointService->calculateRedemptionBreakdown($redemption->points_used);

        return view('admin.redemptions.show', compact('redemption', 'breakdown'));
    }

    /**
     * Selesaikan klaim penukaran (dana telah ditransfer ke peserta)
     */
    public function complete(Request $request, PointRedemption $redemption): RedirectResponse
    {
        if ($redemption->status !== 'pending') {
            return back()->with('error', 'Klaim penukaran ini sudah pernah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
            'receipt' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'receipt.image' => 'Bukti transfer harus berupa file gambar.',
            'receipt.mimes' => 'Format gambar resi harus JPEG, PNG, JPG, atau WEBP.',
            'receipt.max' => 'Ukuran gambar resi maksimal 2MB.',
        ]);

        $receiptPath = $redemption->receipt_image;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $redemption->update([
            'status' => 'completed',
            'notes' => $validated['notes'] ?? 'Pencairan dana telah ditransfer sesuai data rekening/e-wallet peserta.',
            'receipt_image' => $receiptPath,
        ]);

        return redirect()->route('admin.redemptions.index')
            ->with('success', "Klaim penukaran #{$redemption->id} sebesar {$this->pointService->formatRupiah($redemption->rupiah_amount)} berhasil ditandai selesai.");
    }

    /**
     * Tolak klaim penukaran (kembalikan saldo poin ke peserta)
     */
    public function reject(Request $request, PointRedemption $redemption): RedirectResponse
    {
        if ($redemption->status !== 'pending') {
            return back()->with('error', 'Klaim penukaran ini sudah pernah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ], [
            'notes.required' => 'Alasan penolakan pencairan wajib diisi.',
        ]);

        DB::transaction(function () use ($redemption, $validated) {
            // Update status klaim menjadi rejected
            $redemption->update([
                'status' => 'rejected',
                'notes' => $validated['notes'],
            ]);

            // Kembalikan saldo poin yang sempat dipotong ke akun peserta
            $redemption->user->increment('total_points', $redemption->points_used);
        });

        return redirect()->route('admin.redemptions.index')
            ->with('warning', "Klaim penukaran #{$redemption->id} ditolak dan {$redemption->points_used} poin telah dikembalikan ke saldo peserta.");
    }
}
