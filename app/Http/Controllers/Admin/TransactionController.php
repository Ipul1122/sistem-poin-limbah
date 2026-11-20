<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteCategory;
use App\Models\WasteGrade;
use App\Models\WasteTransaction;
use App\Services\PointCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    /**
     * Tampilkan seluruh transaksi limbah dengan filter dan pencarian
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $search = $request->query('search');

        $query = WasteTransaction::with(['user', 'category', 'grade', 'verifier']);

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($search)) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        $categories = WasteCategory::all();
        $grades = WasteGrade::orderBy('grade_code')->get();

        return view('admin.transactions.index', compact('transactions', 'categories', 'grades', 'status', 'categoryId', 'search'));
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show(WasteTransaction $transaction): View
    {
        $transaction->load(['user', 'category', 'grade', 'verifier']);
        $grades = WasteGrade::orderBy('grade_code')->get();

        return view('admin.transactions.show', compact('transaction', 'grades'));
    }

    /**
     * Verifikasi transaksi (Setujui dengan penentuan Grade atau Tolak)
     */
    public function verify(Request $request, WasteTransaction $transaction): RedirectResponse
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah pernah diverifikasi sebelumnya.');
        }

        $action = $request->input('action'); // 'approve' atau 'reject'

        if ($action === 'approve') {
            $validated = $request->validate([
                'grade_id' => ['required', 'exists:waste_grades,id'],
                'notes' => ['nullable', 'string', 'max:500'],
            ], [
                'grade_id.required' => 'Pilih Grade kelayakan (A/B/C/D) untuk menyetujui setoran.',
                'grade_id.exists' => 'Grade kelayakan tidak valid.',
            ]);

            $grade = WasteGrade::findOrFail($validated['grade_id']);

            // Hitung poin menggunakan PointCalculationService (Base Points + Grade Bonus)
            $earnedPoints = $this->pointService->calculateTotalTransactionPoints(
                $transaction->category,
                $transaction->quantity_or_weight,
                $grade
            );

            DB::transaction(function () use ($transaction, $grade, $earnedPoints, $validated) {
                // Update data transaksi
                $transaction->update([
                    'status' => 'approved',
                    'grade_id' => $grade->id,
                    'earned_points' => $earnedPoints,
                    'verified_by' => Auth::id(),
                    'notes' => $validated['notes'] ?? $transaction->notes,
                ]);

                // STRICT: Saldo poin peserta (users.total_points) HANYA bertambah saat status approved
                $transaction->user->increment('total_points', $earnedPoints);
            });

            return redirect()->route('admin.transactions.index')
                ->with('success', "Transaksi #{$transaction->id} berhasil diverifikasi dengan Grade {$grade->grade_code}. Peserta mendapatkan +{$earnedPoints} poin!");
        }

        if ($action === 'reject') {
            $validated = $request->validate([
                'notes' => ['required', 'string', 'max:500'],
            ], [
                'notes.required' => 'Alasan penolakan wajib dicantumkan pada kolom catatan.',
            ]);

            DB::transaction(function () use ($transaction, $validated) {
                $transaction->update([
                    'status' => 'rejected',
                    'verified_by' => Auth::id(),
                    'notes' => $validated['notes'],
                    'earned_points' => 0,
                ]);
            });

            return redirect()->route('admin.transactions.index')
                ->with('warning', "Transaksi #{$transaction->id} telah ditolak. Poin saldo peserta tidak berubah.");
        }

        return back()->with('error', 'Aksi verifikasi tidak valid.');
    }
}
