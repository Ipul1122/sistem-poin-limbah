@extends('layouts.app')

@section('title', 'Dashboard Peserta')
@section('page_title', 'Dashboard Peserta')

@section('content')
<div class="space-y-8">
    <!-- Welcome Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#F97316] to-[#F59E0B] p-6 md:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-xs mb-3">
                    Halo, {{ $user->name }} 👋
                </span>
                <h1 class="font-heading font-extrabold text-2xl md:text-3xl tracking-tight">Selamat Datang di EcoPoint!</h1>
                <p class="text-orange-100 text-sm mt-2 max-w-xl">
                    Tukarkan limbah <strong>Botol</strong> (satuan pcs) dan <strong>Kaleng</strong> (satuan gram) Anda untuk mengumpulkan poin dan menukarkannya langsung menjadi uang Rupiah.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('peserta.transactions.create') }}" class="px-5 py-2.5 rounded-xl bg-white text-[#F97316] font-heading font-bold text-sm shadow hover:bg-orange-50 transition-all flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Setor Limbah</span>
                </a>
                <a href="{{ route('peserta.redemptions.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-heading font-bold text-sm shadow hover:bg-slate-800 transition-all flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Tukar Poin</span>
                </a>
            </div>
        </div>

        <!-- Decorative background circles -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Saldo Poin Card -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Saldo Poin Saat Ini</span>
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#F97316] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-[#1F2937]">
                    {{ number_format($user->total_points, 0, ',', '.') }}
                </div>
                <div class="mt-1 flex items-center text-xs text-emerald-600 font-semibold">
                    <span>Estimasi: Rp {{ number_format($currentEstimatedRupiah, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Setoran -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Setoran</span>
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#3B82F6] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-[#1F2937]">
                    {{ $totalTransactionsCount }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    <span class="text-emerald-600 font-semibold">{{ $approvedCount }} Disetujui</span> &bull;
                    <span class="text-amber-600 font-semibold">{{ $pendingCount }} Pending</span>
                </div>
            </div>
        </div>

        <!-- Poin Ditukarkan -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Poin Dicairkan</span>
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-3xl text-[#1F2937]">
                    {{ number_format($totalRedeemedPoints, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    Poin berhasil ditukar
                </div>
            </div>
        </div>

        <!-- Rupiah Diterima -->
        <div class="bg-white p-6 rounded-2xl border border-[#E5E7EB] shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Rupiah Diterima</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#10B981] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="font-heading font-extrabold text-2xl text-emerald-600">
                    Rp {{ number_format($totalRupiahRedeemed, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500">
                    Sudah dicairkan ke rekening
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Formula & Skema Poin -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center space-x-2 text-[#F97316] font-heading font-bold text-sm mb-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#F97316]"></span>
                <h3>Poin Dasar (Base Point)</h3>
            </div>
            <ul class="text-xs text-gray-600 space-y-1.5">
                <li>&bull; <strong>Botol:</strong> 1 Poin per 1 Botol (pcs)</li>
                <li>&bull; <strong>Kaleng:</strong> 1 Poin per 100 gram berat</li>
            </ul>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center space-x-2 text-[#3B82F6] font-heading font-bold text-sm mb-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                <h3>Grade Kelayakan</h3>
            </div>
            <ul class="text-xs text-gray-600 space-y-1">
                <li>&bull; <span class="font-semibold text-emerald-600">Grade A (Sangat Bagus):</span> +4 Poin</li>
                <li>&bull; <span class="font-semibold text-blue-600">Grade B (Bagus):</span> +3 Poin</li>
                <li>&bull; <span class="font-semibold text-amber-600">Grade C (Cukup):</span> +2 Poin</li>
                <li>&bull; <span class="font-semibold text-red-600">Grade D (Kurang Layak):</span> +1 Poin</li>
            </ul>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <div class="flex items-center space-x-2 text-[#10B981] font-heading font-bold text-sm mb-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                <h3>Konversi Nilai Rupiah</h3>
            </div>
            <ul class="text-xs text-gray-600 space-y-1.5">
                <li>&bull; <strong>20 Poin:</strong> Rp 1.000</li>
                <li>&bull; <strong>10 Poin:</strong> Rp 500</li>
                <li>&bull; <strong>1 Poin:</strong> Rp 100</li>
                <li class="text-[11px] text-gray-400 italic">(Dihitung otomatis dengan pecahan greedy)</li>
            </ul>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="p-6 border-b border-[#E5E7EB] flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-lg text-[#1F2937]">Riwayat Setoran Terbaru</h2>
                <p class="text-xs text-gray-500">5 transaksi setoran limbah terakhir Anda</p>
            </div>
            <a href="{{ route('peserta.transactions.index') }}" class="text-xs font-semibold text-[#F97316] hover:underline flex items-center space-x-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5">ID / Tanggal</th>
                        <th class="px-6 py-3.5">Jenis Limbah</th>
                        <th class="px-6 py-3.5">Jumlah / Berat</th>
                        <th class="px-6 py-3.5">Grade</th>
                        <th class="px-6 py-3.5">Poin Didapat</th>
                        <th class="px-6 py-3.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-orange-50/40 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">
                                <span class="font-bold text-[#1F2937]">#{{ $tx->id }}</span>
                                <span class="block text-[11px] text-gray-400">{{ $tx->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-[#1F2937]">
                                <span class="inline-flex items-center space-x-1.5">
                                    <span class="w-2 h-2 rounded-full {{ $tx->category->name === 'Botol' ? 'bg-blue-500' : 'bg-orange-500' }}"></span>
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold border {{ $badgeColor }}">
                                        Grade {{ $tx->grade->grade_code }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-heading font-bold {{ $tx->status === 'approved' ? 'text-emerald-600' : 'text-gray-400' }}">
                                @if($tx->status === 'approved')
                                    +{{ $tx->earned_points }} Poin
                                @else
                                    0 Poin
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-[#10B981] border border-emerald-200">
                                        Disetujui
                                    </span>
                                @elseif($tx->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-[#F59E0B] border border-amber-200">
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-[#EF4444] border border-red-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-xs">
                                Belum ada riwayat setoran limbah. Klik tombol <strong>Setor Limbah</strong> untuk memulai!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
