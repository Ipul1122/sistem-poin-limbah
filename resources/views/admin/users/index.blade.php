@extends('layouts.app')

@section('title', 'Data Peserta EcoPoint')
@section('page_title', 'Daftar Peserta')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Daftar Peserta Terdaftar</h1>
            <p class="text-xs text-gray-500 mt-1">Pantau keaktifan peserta dan akumulasi saldo poin mereka</p>
        </div>
    </div>

    <!-- Search Box -->
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-xs max-w-md">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center space-x-2 text-xs">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email peserta..."
                   class="flex-1 px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#F97316]">
            <button type="submit" class="px-4 py-2 rounded-xl font-heading font-bold text-white bg-slate-900 hover:bg-slate-800 transition">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Saldo Poin</th>
                        <th class="px-6 py-4">Estimasi Rupiah</th>
                        <th class="px-6 py-4">Total Setoran</th>
                        <th class="px-6 py-4">Total Penukaran</th>
                        <th class="px-6 py-4">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($users as $u)
                        @php
                            $calc = app(\App\Services\PointCalculationService::class);
                            $estRupiah = $calc->convertPointsToRupiah($u->total_points);
                        @endphp
                        <tr class="hover:bg-orange-50/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">#{{ $u->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-gray-800 text-sm">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-600 text-xs">
                                {{ $u->email }}
                            </td>
                            <td class="px-6 py-4 font-heading font-extrabold text-base text-[#F97316]">
                                {{ number_format($u->total_points, 0, ',', '.') }} Poin
                            </td>
                            <td class="px-6 py-4 font-heading font-bold text-emerald-600 text-xs">
                                Rp {{ number_format($estRupiah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-700">
                                {{ $u->waste_transactions_count }}x setoran
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-gray-700">
                                {{ $u->point_redemptions_count }}x klaim
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-xs">
                                Tidak ada data peserta yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#E5E7EB]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
