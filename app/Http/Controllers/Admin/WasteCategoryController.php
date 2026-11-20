<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WasteCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasteCategoryController extends Controller
{
    /**
     * Tampilkan master data kategori limbah & parameter poin dasar
     */
    public function index(): View
    {
        $categories = WasteCategory::all();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Tampilkan form edit parameter kategori
     */
    public function edit(WasteCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update parameter kategori (unit_divisor, base_point)
     */
    public function update(Request $request, WasteCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'unit_divisor' => ['required', 'integer', 'min:1'],
            'base_point' => ['required', 'integer', 'min:1'],
        ], [
            'unit_divisor.required' => 'Nilai pembagi satuan wajib diisi.',
            'unit_divisor.min' => 'Pembagi minimal 1.',
            'base_point.required' => 'Poin dasar wajib diisi.',
            'base_point.min' => 'Poin dasar minimal 1.',
        ]);

        $category->update([
            'unit_divisor' => $validated['unit_divisor'],
            'base_point' => $validated['base_point'],
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Parameter kategori {$category->name} berhasil diperbarui.");
    }
}
