@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 md:p-8 rounded-2xl text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-xs font-semibold backdrop-blur-xs mb-2">
                Panel Kontrol Administrator
            </span>
            <h1 class="font-heading font-extrabold text-2xl md:text-3xl tracking-tight">EcoPoint Management System</h1>
            <p class="text-slate-300 text-xs md:text-sm mt-1">
                Kelola verifikasi penukaran limbah, tentukan grade kelayakan, dan pantau pencairan poin peserta ke Rupiah.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] shadow hover:opacity-95 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span>Verifikasi Limbah ({{ $pendingTransactionsCount }})</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Transaksi Pending -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Verifikasi</span>
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-[#F59E0B] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-[#F59E0B]">
                    {{ $pendingTransactionsCount }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    Setoran butuh penentuan grade
                </div>
            </div>
        </div>

        <!-- Penukaran Poin Pending -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Klaim Pencairan Pending</span>
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-red-600">
                    {{ $pendingRedemptionsCount }}
                </div>
                <div class="mt-1 text-xs text-emerald-600 font-semibold">
                    Senilai Rp {{ number_format($pendingRedemptionRupiah, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Total Poin Beredar -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Poin di Peserta</span>
                <div class="w-10 h-10 rounded-xl bg-pink-100 text-[#EC4899] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-[#1F2937]">
                    {{ number_format($totalActivePoints, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    Dari {{ $totalPeserta }} Peserta aktif
                </div>
            </div>
        </div>

        <!-- Total Transaksi Selesai -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Setoran Disetujui</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#10B981] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-emerald-600">
                    {{ $approvedTransactionsCount }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    +{{ number_format($totalApprovedPoints, 0, ',', '.') }} Poin tersalurkan
                </div>
            </div>
        </div>
    </div>

    <!-- Two-column tables: Pending Verifications & Pending Redemptions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Transactions Table -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden flex flex-col">
            <div class="p-5 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-bold text-base text-[#1F2937]">Setoran Menunggu Verifikasi</h2>
                    <p class="text-xs text-gray-500">Tentukan grade kelayakan (A/B/C/D) untuk menambah poin</p>
                </div>
                <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-[#EC4899] hover:underline">
                    Semua
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 text-[10px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                        <tr>
                            <th class="px-4 py-3">Peserta</th>
                            <th class="px-4 py-3">Limbah</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($pendingTransactions as $pt)
                            <tr class="hover:bg-pink-50/30 transition">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-800 block">{{ $pt->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $pt->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $pt->category->name }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ number_format($pt->quantity_or_weight, 0, ',', '.') }} {{ $pt->category->unit_type }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.transactions.show', $pt) }}"
                                       class="inline-block px-3 py-1 rounded-lg font-heading font-bold text-[11px] text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow-xs transition">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">
                                    Tidak ada setoran yang menunggu verifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Redemptions Table -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden flex flex-col">
            <div class="p-5 border-b border-[#E5E7EB] flex items-center justify-between">
                <div>
                    <h2 class="font-heading font-bold text-base text-[#1F2937]">Klaim Pencairan Menunggu Transfer</h2>
                    <p class="text-xs text-gray-500">Permintaan penukaran poin ke Rupiah dari peserta</p>
                </div>
                <a href="{{ route('admin.redemptions.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-[#EC4899] hover:underline">
                    Semua
                </a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 text-[10px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                        <tr>
                            <th class="px-4 py-3">Peserta</th>
                            <th class="px-4 py-3">Poin Ditukar</th>
                            <th class="px-4 py-3">Nominal Rupiah</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        @forelse($pendingRedemptions as $pr)
                            <tr class="hover:bg-pink-50/30 transition">
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-800 block">{{ $pr->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $pr->payment_info }}</span>
                                </td>
                                <td class="px-4 py-3 font-bold text-gray-800">
                                    {{ number_format($pr->points_used, 0, ',', '.') }} Poin
                                </td>
                                <td class="px-4 py-3 font-heading font-bold text-emerald-600">
                                    Rp {{ number_format($pr->rupiah_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.redemptions.show', $pr) }}"
                                       class="inline-block px-3 py-1 rounded-lg font-heading font-bold text-[11px] text-white bg-slate-900 hover:bg-slate-800 shadow-xs transition">
                                        Proses
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">
                                    Tidak ada klaim pencairan yang pending.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
