<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\WasteCategory;
use App\Models\WasteTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WasteTransactionController extends Controller
{
    /**
     * Tampilkan riwayat setoran limbah peserta
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $transactionsQuery = WasteTransaction::with(['category', 'grade', 'verifier'])
            ->where('user_id', Auth::id());

        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $transactionsQuery->where('status', $status);
        }

        $transactions = $transactionsQuery->latest()->paginate(10)->withQueryString();

        return view('peserta.transactions.index', compact('transactions', 'status'));
    }

    /**
     * Tampilkan form pengajuan setoran limbah
     */
    public function create(): View
    {
        // STRICT: Hanya ambil kategori Botol dan Kaleng sesuai Rules.md
        $categories = WasteCategory::whereIn('name', ['Botol', 'Kaleng'])->get();

        return view('peserta.transactions.create', compact('categories'));
    }

    /**
     * Simpan pengajuan setoran limbah baru
     */
    public function store(Request $request): RedirectResponse
    {
        $category = WasteCategory::findOrFail($request->input('category_id'));

        // STRICT Validation: Pastikan kategori hanya Botol atau Kaleng
        if (!in_array($category->name, ['Botol', 'Kaleng'])) {
            throw ValidationException::withMessages([
                'category_id' => 'Kategori limbah tidak sah. Sistem hanya menerima Botol dan Kaleng.',
            ]);
        }

        // Aturan validasi dinamis berdasarkan kategori (Botol: pcs, Kaleng: gram)
        $minVal = $category->name === 'Kaleng' ? 100 : 1;
        $unitLabel = $category->unit_type === 'gram' ? 'gram (minimal 100 gram)' : 'pcs (minimal 1 pcs)';

        $validated = $request->validate([
            'category_id' => ['required', 'exists:waste_categories,id'],
            'quantity_or_weight' => ['required', 'integer', 'min:' . $minVal],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'category_id.required' => 'Pilih kategori limbah.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'quantity_or_weight.required' => 'Jumlah/berat limbah wajib diisi.',
            'quantity_or_weight.integer' => 'Jumlah/berat harus berupa bilangan bulat.',
            'quantity_or_weight.min' => "Jumlah/berat untuk {$category->name} wajib dalam satuan {$unitLabel}.",
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar yang didukung adalah JPEG, PNG, JPG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 2MB (2048 KB).',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('waste_images', 'public');
        }

        // STRICT: Status awal adalah 'pending', poin didapat = 0, poin saldo TIDAK bertambah
        WasteTransaction::create([
            'user_id' => Auth::id(),
            'verified_by' => null,
            'category_id' => $category->id,
            'grade_id' => null,
            'quantity_or_weight' => $validated['quantity_or_weight'],
            'earned_points' => 0,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('peserta.transactions.index')
            ->with('success', 'Pengajuan penukaran limbah berhasil dikirim! Menunggu verifikasi dan penentuan grade oleh Administrator.');
    }
}
