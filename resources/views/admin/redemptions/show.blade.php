@extends('layouts.app')

@section('title', 'Detail Pencairan #' . $redemption->id)
@section('page_title', 'Detail Pencairan Dana Poin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.redemptions.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-[#F97316] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Penukaran</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="p-6 md:p-8 border-b border-[#E5E7EB] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="font-heading font-extrabold text-2xl text-[#1F2937]">Klaim Pencairan #{{ $redemption->id }}</h1>
                    @if($redemption->status === 'completed')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Selesai Ditransfer
                        </span>
                    @elseif($redemption->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                            Menunggu Transfer
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                            Ditolak
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-1">Diajukan pada {{ $redemption->created_at->format('d F Y, H:i:s') }}</p>
            </div>

            <div class="text-right">
                <span class="text-xs text-gray-400 block">Total Nilai Pencairan:</span>
                <span class="font-heading font-extrabold text-3xl text-emerald-600 block">
                    Rp {{ number_format($redemption->rupiah_amount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            <!-- Peserta & Payment Info Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs">
                    <span class="font-semibold text-gray-500 uppercase tracking-wider block mb-2">Informasi Peserta</span>
                    <div class="space-y-1">
                        <span class="font-bold text-gray-800 text-sm block">{{ $redemption->user->name }}</span>
                        <span class="text-gray-600 block">{{ $redemption->user->email }}</span>
                        <span class="text-[#F97316] font-semibold block">Sisa Saldo: {{ $redemption->user->total_points }} Poin</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-orange-50/50 border border-orange-200 text-xs">
                    <span class="font-semibold text-orange-800 uppercase tracking-wider block mb-2">Tujuan Transfer / Pencairan</span>
                    <div class="font-bold text-gray-900 text-sm p-2 rounded-lg bg-white border border-orange-200">
                        {{ $redemption->payment_info }}
                    </div>
                </div>
            </div>

            <!-- Greedy Breakdown Details -->
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs">
                <span class="font-semibold text-gray-700 block mb-3">Rincian Perhitungan Greedy Konversi ({{ $redemption->points_used }} Poin):</span>
                <div class="grid grid-cols-3 gap-3 text-center mb-2">
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <span class="text-[10px] text-gray-400 block">Pecahan 20 Poin</span>
                        <span class="font-bold text-sm text-gray-800">{{ $breakdown['denominations']['20']['count'] }}x</span>
                        <span class="text-[11px] text-emerald-600 font-semibold block">Rp {{ number_format($breakdown['denominations']['20']['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <span class="text-[10px] text-gray-400 block">Pecahan 10 Poin</span>
                        <span class="font-bold text-sm text-gray-800">{{ $breakdown['denominations']['10']['count'] }}x</span>
                        <span class="text-[11px] text-emerald-600 font-semibold block">Rp {{ number_format($breakdown['denominations']['10']['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                        <span class="text-[10px] text-gray-400 block">Pecahan 1 Poin</span>
                        <span class="font-bold text-sm text-gray-800">{{ $breakdown['denominations']['1']['count'] }}x</span>
                        <span class="text-[11px] text-emerald-600 font-semibold block">Rp {{ number_format($breakdown['denominations']['1']['subtotal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($redemption->status !== 'pending')
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs">
                    <span class="font-semibold text-gray-700 block mb-1">Catatan Proses:</span>
                    <p class="text-gray-600">{{ $redemption->notes ?: 'Tidak ada catatan.' }}</p>
                    <span class="block text-[10px] text-gray-400 mt-2">Diproses pada: {{ $redemption->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            @else
                <!-- Processing Forms -->
                <div class="border-t border-[#E5E7EB] pt-6 space-y-6">
                    <div>
                        <h2 class="font-heading font-bold text-base text-[#1F2937] mb-1">Proses Pencairan Dana</h2>
                        <p class="text-xs text-gray-500">
                            Silakan lakukan transfer ke rekening/e-wallet di atas. Setelah transfer berhasil, klik Konfirmasi Selesai.
                        </p>
                    </div>

                    <form action="{{ route('admin.redemptions.complete', $redemption) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="complete_notes" class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Bukti Transfer (Opsional)</label>
                            <input type="text" id="complete_notes" name="notes" placeholder="Contoh: No. Ref Transfer BCA 98127391283"
                                   class="w-full px-4 py-2 rounded-xl border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-[#10B981]">
                        </div>
                        <button type="submit"
                                onclick="return confirm('Pastikan dana sebesar Rp {{ number_format($redemption->rupiah_amount, 0, ',', '.') }} telah ditransfer ke rekening peserta. Lanjutkan?');"
                                class="w-full py-3 px-4 rounded-xl font-heading font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-700 shadow transition">
                            Konfirmasi Transfer Telah Berhasil Selesai
                        </button>
                    </form>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="button" onclick="document.getElementById('reject_container').classList.toggle('hidden');"
                                class="text-xs font-semibold text-red-600 hover:underline">
                            Tolak Permintaan Pencairan Ini & Refund Poin ke Peserta
                        </button>

                        <div id="reject_container" class="hidden mt-3 p-4 bg-red-50 rounded-xl border border-red-200">
                            <form action="{{ route('admin.redemptions.reject', $redemption) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-red-800 mb-1">Alasan Penolakan <span class="text-red-600">*</span></label>
                                    <textarea name="notes" rows="2" required placeholder="Contoh: Nomor rekening tidak valid / tidak terdaftar di bank terkait."
                                              class="w-full px-3 py-2 rounded-xl border border-red-300 text-xs focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
                                </div>
                                <button type="submit"
                                        onclick="return confirm('Yakin ingin menolak permintaan ini? Poin sebesar {{ $redemption->points_used }} akan dikembalikan ke saldo peserta.');"
                                        class="px-4 py-2 rounded-xl font-heading font-bold text-xs text-white bg-red-600 hover:bg-red-700 transition">
                                    Konfirmasi Penolakan & Refund Saldo Poin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
