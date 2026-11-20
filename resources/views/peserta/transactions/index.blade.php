@extends('layouts.app')

@section('title', 'Riwayat Setoran Limbah')
@section('page_title', 'Riwayat Setoran Limbah')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Daftar Setoran Limbah Anda</h1>
            <p class="text-xs text-gray-500 mt-1">Pantau status verifikasi dan perolehan poin dari setiap limbah yang disetor</p>
        </div>
        <div>
            <a href="{{ route('peserta.transactions.create') }}"
               class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F97316] to-[#F59E0B] shadow hover:opacity-95 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Setor Limbah Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="flex flex-wrap gap-2 text-xs">
        <a href="{{ route('peserta.transactions.index') }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ empty($status) ? 'bg-[#1F2937] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Semua Status
        </a>
        <a href="{{ route('peserta.transactions.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'pending' ? 'bg-[#F59E0B] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Menunggu Verifikasi (Pending)
        </a>
        <a href="{{ route('peserta.transactions.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'approved' ? 'bg-[#10B981] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Disetujui (Approved)
        </a>
        <a href="{{ route('peserta.transactions.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'rejected' ? 'bg-[#EF4444] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Ditolak (Rejected)
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-4">ID / Tanggal</th>
                        <th class="px-6 py-4">Jenis Limbah</th>
                        <th class="px-6 py-4">Jumlah / Berat</th>
                        <th class="px-6 py-4">Grade Kelayakan</th>
                        <th class="px-6 py-4">Poin Diperoleh</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Catatan / Verifikator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-orange-50/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">
                                <span class="font-bold text-[#1F2937]">#{{ $tx->id }}</span>
                                <span class="block text-[11px] text-gray-400">{{ $tx->created_at->format('d/m/Y H:i') }}</span>
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
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold border {{ $badgeColor }}">
                                            Grade {{ $tx->grade->grade_code }}
                                        </span>
                                        <span class="block text-[10px] text-gray-500 mt-0.5">{{ $tx->grade->label }} (+{{ $tx->grade->bonus_point }} poin)</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Menunggu verifikasi admin</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-heading font-bold text-sm">
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
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-[#EF4444] border border-red-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($tx->notes)
                                    <p class="text-gray-700 max-w-xs break-words">{{ $tx->notes }}</p>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif

                                @if($tx->verifier)
                                    <span class="block text-[10px] text-gray-400 mt-0.5">Diverifikasi: {{ $tx->verifier->name }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-xs">
                                Tidak ada data setoran limbah yang ditemukan.
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
