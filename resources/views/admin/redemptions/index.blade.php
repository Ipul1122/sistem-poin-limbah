@extends('layouts.app')

@section('title', 'Kelola Penukaran Poin ke Rupiah')
@section('page_title', 'Klaim Penukaran Poin ke Rupiah')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Permintaan Pencairan Saldo Rupiah</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola dan transfer dana klaim penukaran poin peserta</p>
        </div>
    </div>

    <!-- Filter Status -->
    <div class="flex flex-wrap gap-2 text-xs">
        <a href="{{ route('admin.redemptions.index') }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ empty($status) ? 'bg-[#1F2937] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Semua Status
        </a>
        <a href="{{ route('admin.redemptions.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'pending' ? 'bg-[#F59E0B] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Menunggu Transfer (Pending)
        </a>
        <a href="{{ route('admin.redemptions.index', ['status' => 'completed']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'completed' ? 'bg-[#10B981] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Selesai Ditransfer
        </a>
        <a href="{{ route('admin.redemptions.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-xl font-medium transition {{ $status === 'rejected' ? 'bg-[#EF4444] text-white' : 'bg-white text-gray-600 border border-[#E5E7EB] hover:bg-gray-50' }}">
            Ditolak (Poin Refunded)
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-4">ID / Tanggal</th>
                        <th class="px-6 py-4">Peserta</th>
                        <th class="px-6 py-4">Poin Ditukar</th>
                        <th class="px-6 py-4">Nominal Rupiah</th>
                        <th class="px-6 py-4">Tujuan Pencairan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($redemptions as $item)
                        <tr class="hover:bg-orange-50/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">
                                <span class="font-bold text-[#1F2937]">#{{ $item->id }}</span>
                                <span class="block text-[11px] text-gray-400">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-800 block">{{ $item->user->name }}</span>
                                <span class="text-[11px] text-gray-400">{{ $item->user->email }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ number_format($item->points_used, 0, ',', '.') }} Poin
                            </td>
                            <td class="px-6 py-4 font-heading font-extrabold text-emerald-600">
                                Rp {{ number_format($item->rupiah_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-700">
                                {{ $item->payment_info }}
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-[#10B981] border border-emerald-200">
                                        Selesai Ditransfer
                                    </span>
                                @elseif($item->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-[#F59E0B] border border-amber-200">
                                        Menunggu Transfer
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-[#EF4444] border border-red-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.redemptions.show', $item) }}"
                                   class="inline-block px-3 py-1.5 rounded-xl font-heading font-bold text-xs {{ $item->status === 'pending' ? 'bg-gradient-to-r from-[#F97316] to-[#F59E0B] text-white shadow-xs hover:opacity-95' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                                    {{ $item->status === 'pending' ? 'Proses Transfer' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-xs">
                                Tidak ada permintaan pencairan poin yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($redemptions->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
