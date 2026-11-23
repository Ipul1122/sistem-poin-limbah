@extends('layouts.app')

@section('title', 'Master Data Grade Kelayakan')
@section('page_title', 'Master Data Grade Kelayakan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Grade Kelayakan & Bonus Poin</h1>
            <p class="text-xs text-gray-500 mt-1">Parameter penilaian kondisi fisik limbah saat verifikasi transaksi oleh admin</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                <tr>
                    <th class="px-6 py-4">Kode Grade</th>
                    <th class="px-6 py-4">Label Deskripsi</th>
                    <th class="px-6 py-4">Bonus Poin Transaksi</th>
                    <th class="px-6 py-4">Warna Badge</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E7EB]">
                @foreach($grades as $gr)
                    @php
                        $badgeClass = match($gr->grade_code) {
                            'A' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'B' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'C' => 'bg-amber-100 text-amber-800 border-amber-200',
                            default => 'bg-red-100 text-red-800 border-red-200',
                        };
                    @endphp
                    <tr class="hover:bg-pink-50/30 transition">
                        <td class="px-6 py-4 font-mono font-bold text-gray-800">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border {{ $badgeClass }}">
                                Grade {{ $gr->grade_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $gr->label }}
                        </td>
                        <td class="px-6 py-4 font-heading font-extrabold text-base text-[#EC4899]">
                            +{{ $gr->bonus_point }} Poin
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                            {{ $gr->grade_code === 'A' ? '#10B981 (Emerald)' : ($gr->grade_code === 'B' ? '#3B82F6 (Blue)' : ($gr->grade_code === 'C' ? '#F59E0B (Amber)' : '#EF4444 (Red)')) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.grades.edit', $gr) }}"
                               class="inline-block px-3 py-1.5 rounded-xl font-heading font-bold text-xs text-[#EC4899] bg-pink-50 hover:bg-pink-100 border border-pink-200 transition">
                                Edit Grade
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
