@extends('layouts.app')

@section('title', 'Edit Parameter Kategori ' . $category->name)
@section('page_title', 'Edit Parameter Kategori')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-[#F97316] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Master Kategori</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-6 md:p-8">
        <h1 class="font-heading font-bold text-xl text-[#1F2937] mb-1">Edit Parameter: {{ $category->name }}</h1>
        <p class="text-xs text-gray-500 mb-6">Sesuaikan pembagi satuan dan poin dasar yang dihasilkan.</p>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" value="{{ $category->name }}" disabled
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed">
                <span class="text-[11px] text-gray-400 mt-1 block">Nama kategori tidak dapat diubah (Strict Domain Limbah: Botol & Kaleng).</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Satuan</label>
                <input type="text" value="{{ strtoupper($category->unit_type) }}" disabled
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed font-mono">
            </div>

            <div>
                <label for="unit_divisor" class="block text-xs font-semibold text-gray-700 mb-1">Pembagi Satuan (Unit Divisor) <span class="text-red-500">*</span></label>
                <input type="number" id="unit_divisor" name="unit_divisor" value="{{ old('unit_divisor', $category->unit_divisor) }}" required min="1"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316] transition">
                <p class="text-[11px] text-gray-500 mt-1">Contoh: 1 untuk Botol (per 1 pcs), 100 untuk Kaleng (per 100 gram).</p>
            </div>

            <div>
                <label for="base_point" class="block text-xs font-semibold text-gray-700 mb-1">Poin Dasar (Base Point) <span class="text-red-500">*</span></label>
                <input type="number" id="base_point" name="base_point" value="{{ old('base_point', $category->base_point) }}" required min="1"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316] transition">
                <p class="text-[11px] text-gray-500 mt-1">Jumlah poin dasar yang diperoleh untuk setiap kelipatan pembagi satuan di atas.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-[#E5E7EB]">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F97316] to-[#F59E0B] shadow hover:opacity-95 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
