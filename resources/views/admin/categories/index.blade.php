@extends('layouts.app')

@section('title', 'Master Data Kategori Limbah')
@section('page_title', 'Master Data Kategori Limbah')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Kategori Limbah & Parameter Poin Dasar</h1>
            <p class="text-xs text-gray-500 mt-1">Sesuai aturan sistem, hanya kategori <strong>Botol</strong> dan <strong>Kaleng</strong> yang diperbolehkan.</p>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="p-4 rounded-xl bg-orange-50/50 border border-orange-200 text-xs text-orange-950 flex items-start space-x-3">
        <svg class="w-5 h-5 text-[#F97316] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <span class="font-semibold">Aturan Bisnis Domain Limbah (Strict):</span>
            Sistem hanya menerima dua jenis limbah: <strong>Botol</strong> (satuan pcs) dan <strong>Kaleng</strong> (satuan gram). Penambahan jenis limbah lain dilarang oleh aturan arsitektur. Anda dapat menyesuaikan nilai pembagi satuan (unit divisor) dan rasio poin dasar per satuan.
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Nama Kategori</th>
                    <th class="px-6 py-4">Tipe Satuan</th>
                    <th class="px-6 py-4">Pembagi Satuan (Divisor)</th>
                    <th class="px-6 py-4">Poin Dasar</th>
                    <th class="px-6 py-4">Rumus Perhitungan</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E5E7EB]">
                @foreach($categories as $cat)
                    <tr class="hover:bg-orange-50/30 transition">
                        <td class="px-6 py-4 font-mono text-xs text-gray-700">#{{ $cat->id }}</td>
                        <td class="px-6 py-4 font-heading font-bold text-gray-800">
                            {{ $cat->name }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs uppercase font-semibold text-gray-700">
                            {{ $cat->unit_type }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $cat->unit_divisor }} {{ $cat->unit_type }}
                        </td>
                        <td class="px-6 py-4 font-heading font-bold text-[#F97316]">
                            {{ $cat->base_point }} Poin
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-500">
                            (Jumlah / {{ $cat->unit_divisor }}) &times; {{ $cat->base_point }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="inline-block px-3 py-1.5 rounded-xl font-heading font-bold text-xs text-[#F97316] bg-orange-50 hover:bg-orange-100 border border-orange-200 transition">
                                Edit Parameter
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
