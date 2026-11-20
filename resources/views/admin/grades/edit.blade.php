@extends('layouts.app')

@section('title', 'Edit Grade ' . $grade->grade_code)
@section('page_title', 'Edit Grade Kelayakan')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.grades.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-[#F97316] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Master Grade</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs p-6 md:p-8">
        <h1 class="font-heading font-bold text-xl text-[#1F2937] mb-1">Edit Parameter: Grade {{ $grade->grade_code }}</h1>
        <p class="text-xs text-gray-500 mb-6">Ubah label deskripsi dan nilai bonus poin untuk grade ini.</p>

        <form action="{{ route('admin.grades.update', $grade) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Grade</label>
                <input type="text" value="Grade {{ $grade->grade_code }}" disabled
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed font-bold">
            </div>

            <div>
                <label for="label" class="block text-xs font-semibold text-gray-700 mb-1">Label Deskripsi <span class="text-red-500">*</span></label>
                <input type="text" id="label" name="label" value="{{ old('label', $grade->label) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316] transition">
                <p class="text-[11px] text-gray-500 mt-1">Contoh: Sangat Bagus, Bagus, Cukup, Kurang Layak.</p>
            </div>

            <div>
                <label for="bonus_point" class="block text-xs font-semibold text-gray-700 mb-1">Bonus Poin Transaksi <span class="text-red-500">*</span></label>
                <input type="number" id="bonus_point" name="bonus_point" value="{{ old('bonus_point', $grade->bonus_point) }}" required min="0"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316] transition">
                <p class="text-[11px] text-gray-500 mt-1">Poin tambahan yang langsung ditambahkan ke transaksi jika grade ini dipilih.</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-[#E5E7EB]">
                <a href="{{ route('admin.grades.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
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
