@extends('layouts.app')

@section('title', 'Kelola Transaksi Limbah')
@section('page_title', 'Verifikasi & Transaksi Limbah')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Semua Setoran Limbah</h1>
            <p class="text-xs text-gray-500 mt-1">Verifikasi setoran limbah peserta dan tetapkan Grade kelayakan</p>
        </div>
    </div>

    <!-- Filters & Search Form -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs">
            <!-- Filter Status -->
            <div>
                <label class="block font-semibold text-gray-600 mb-1">Status Verifikasi</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#EC4899]">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label class="block font-semibold text-gray-600 mb-1">Kategori Limbah</label>
                <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#EC4899]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Cari Nama/Email Peserta -->
            <div>
                <label class="block font-semibold text-gray-600 mb-1">Cari Peserta</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau email..."
                       class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#EC4899]">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl font-heading font-bold text-xs text-white bg-slate-900 hover:bg-slate-800 transition">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="py-2 px-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-4">ID / Tanggal</th>
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">Peserta</th>
                        <th class="px-6 py-4">Jenis Limbah</th>
                        <th class="px-6 py-4">Jumlah / Berat</th>
                        <th class="px-6 py-4">Grade</th>
                        <th class="px-6 py-4">Poin Dihasilkan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-pink-50/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">
                                <span class="font-bold text-[#1F2937]">#{{ $tx->id }}</span>
                                <span class="block text-[11px] text-gray-400">{{ $tx->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->image_path)
                                    <a href="{{ $tx->image_url }}" target="_blank" class="block w-12 h-12 rounded-xl overflow-hidden border border-pink-200 hover:scale-105 transition shadow-xs">
                                        <img src="{{ $tx->image_url }}" alt="Foto #{{ $tx->id }}" class="w-full h-full object-cover">
                                    </a>
                                @else
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-400 text-xs">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800 block">{{ $tx->user->name }}</span>
                                <span class="text-[11px] text-gray-400">{{ $tx->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                <span class="inline-flex items-center space-x-1.5">
                                    <span class="w-2 h-2 rounded-full {{ $tx->category->name === 'Botol' ? 'bg-blue-500' : 'bg-pink-500' }}"></span>
                                    <span>{{ $tx->category->name }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ number_format($tx->quantity_or_weight, 0, ',', '.') }} {{ $tx->category->unit_type }}
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->grade)
                                    @php
                                        $badgeColor = match($tx->grade->grade_code) {
                                            'A' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'B' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'C' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            default => 'bg-red-100 text-red-800 border-red-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold border {{ $badgeColor }}">
                                        Grade {{ $tx->grade->grade_code }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-heading font-bold">
                                @if($tx->status === 'approved')
                                    <span class="text-emerald-600">+{{ $tx->earned_points }} Poin</span>
                                @else
                                    <span class="text-gray-400">0 Poin</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-[#10B981] border border-emerald-200">
                                        Disetujui
                                    </span>
                                @elseif($tx->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-[#F59E0B] border border-amber-200">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-[#EF4444] border border-red-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.transactions.show', $tx) }}"
                                   class="inline-block px-3 py-1.5 rounded-xl font-heading font-bold text-xs {{ $tx->status === 'pending' ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-xs hover:opacity-95' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                                    {{ $tx->status === 'pending' ? 'Verifikasi' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400 text-xs">
                                Tidak ada data transaksi limbah yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
