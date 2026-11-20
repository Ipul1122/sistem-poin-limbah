<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteGrade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasteGradeController extends Controller
{
    /**
     * Tampilkan data grade kelayakan limbah & bonus poin
     */
    public function index(): View
    {
        $grades = WasteGrade::orderBy('grade_code')->get();

        return view('admin.grades.index', compact('grades'));
    }

    /**
     * Tampilkan form edit grade
     */
    public function edit(WasteGrade $grade): View
    {
        return view('admin.grades.edit', compact('grade'));
    }

    /**
     * Update parameter bonus poin dan label grade
     */
    public function update(Request $request, WasteGrade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'bonus_point' => ['required', 'integer', 'min:0'],
        ], [
            'label.required' => 'Label deskripsi grade wajib diisi.',
            'bonus_point.required' => 'Bonus poin wajib diisi.',
            'bonus_point.min' => 'Bonus poin minimal 0.',
        ]);

        $grade->update([
            'label' => $validated['label'],
            'bonus_point' => $validated['bonus_point'],
        ]);

        return redirect()->route('admin.grades.index')
            ->with('success', "Data Grade {$grade->grade_code} berhasil diperbarui.");
    }
}
