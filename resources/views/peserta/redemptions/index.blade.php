@extends('layouts.app')

@section('title', 'Tukar Poin ke Rupiah')
@section('page_title', 'Tukar Poin ke Rupiah')

@section('content')
<div class="space-y-8">
    <!-- Top Grid: Balance & Redemption Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Balance & Conversion Guide Card -->
        <div class="space-y-6">
            <!-- Saldo Card -->
            <div class="bg-gradient-to-tr from-slate-900 to-slate-800 p-6 rounded-2xl text-white shadow-md relative overflow-hidden">
                <span class="text-xs uppercase tracking-wider text-slate-400 font-semibold block mb-1">Saldo Poin Anda</span>
                <div class="font-heading font-extrabold text-4xl text-[#F59E0B]">
                    {{ number_format($user->total_points, 0, ',', '.') }}
                    <span class="text-sm font-normal text-white">Poin</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-700/60 flex items-center justify-between text-xs">
                    <span class="text-slate-300">Estimasi Total Cair:</span>
                    <span class="font-bold text-emerald-400 text-sm">
                        {{ $breakdown['formatted_rupiah'] }}
                    </span>
                </div>
            </div>

            <!-- Conversion Rules Guide -->
            <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-xs">
                <h3 class="font-heading font-bold text-sm text-[#1F2937] mb-3 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>
                    <span>Skema Pecahan Greedy Rupiah</span>
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="font-medium text-gray-700">Denominasi 20 Poin</span>
                        <span class="font-bold text-emerald-600">Rp 1.000</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="font-medium text-gray-700">Denominasi 10 Poin</span>
                        <span class="font-bold text-emerald-600">Rp 500</span>
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-100">
                        <span class="font-medium text-gray-700">Denominasi 1 Poin</span>
                        <span class="font-bold text-emerald-600">Rp 100</span>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 mt-3 leading-relaxed">
                    Sistem memprioritaskan pecahan terbesar terlebih dahulu: kelipatan 20 poin, lalu 10 poin, kemudian sisanya 1 poin.
                </p>
            </div>
        </div>

        <!-- Redemption Form Card (2 Columns Wide on LG) -->
        <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-2xl border border-[#E5E7EB] shadow-xs">
            <h2 class="font-heading font-bold text-lg text-[#1F2937] mb-1">Form Pengajuan Penukaran Poin</h2>
            <p class="text-xs text-gray-500 mb-6">Tukarkan poin Anda menjadi saldo Rupiah ke Bank atau E-Wallet pilihan Anda.</p>

            @if($user->total_points < 1)
                <div class="p-5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-start space-x-3">
                    <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <span class="font-bold">Saldo Poin Anda Kosong (0 Poin).</span>
                        <p class="mt-1">Silakan setor limbah terlebih dahulu melalui menu <strong>Setor Limbah</strong> untuk mengumpulkan poin sebelum melakukan penukaran.</p>
                    </div>
                </div>
            @else
                <form action="{{ route('peserta.redemptions.store') }}" method="POST" class="space-y-5" id="redemption_form">
                    @csrf

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="points_used" class="block text-xs font-semibold text-gray-700">
                                Jumlah Poin yang Ingin Ditukar <span class="text-red-500">*</span>
                            </label>
                            <button type="button" id="btn_all_points" class="text-xs font-semibold text-[#EC4899] hover:underline">
                                Tukar Semua ({{ $user->total_points }} Poin)
                            </button>
                        </div>
                        <input type="number" id="points_used" name="points_used"
                               value="{{ old('points_used', min(20, $user->total_points)) }}"
                               min="1" max="{{ $user->total_points }}" step="1" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                        <p class="text-[11px] text-gray-500 mt-1">
                            Maksimal penukaran adalah saldo yang Anda miliki saat ini (<strong>{{ $user->total_points }} Poin</strong>).
                        </p>
                    </div>

                    <!-- Dynamic Greedy Calculation Preview Box -->
                    <div class="p-4 rounded-xl bg-pink-50/50 border border-pink-200">
                        <span class="text-xs font-semibold text-gray-700 block mb-2">Rincian Perhitungan Konversi (Greedy):</span>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                            <div class="bg-white p-2 rounded-lg border border-pink-100">
                                <span class="block text-gray-400 text-[10px]">Pecahan 20 Poin</span>
                                <span id="calc_p20" class="font-bold text-gray-800 text-sm">0x</span>
                                <span id="calc_sub20" class="block text-[10px] text-emerald-600 font-semibold">Rp 0</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-pink-100">
                                <span class="block text-gray-400 text-[10px]">Pecahan 10 Poin</span>
                                <span id="calc_p10" class="font-bold text-gray-800 text-sm">0x</span>
                                <span id="calc_sub10" class="block text-[10px] text-emerald-600 font-semibold">Rp 0</span>
                            </div>
                            <div class="bg-white p-2 rounded-lg border border-pink-100">
                                <span class="block text-gray-400 text-[10px]">Pecahan 1 Poin</span>
                                <span id="calc_p1" class="font-bold text-gray-800 text-sm">0x</span>
                                <span id="calc_sub1" class="block text-[10px] text-emerald-600 font-semibold">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-pink-200/60">
                            <span class="text-xs font-bold text-gray-700">Total Nilai Rupiah Diterima:</span>
                            <span id="calc_total" class="font-heading font-extrabold text-base text-[#EC4899]">
                                Rp 0
                            </span>
                        </div>
                    </div>

                    <!-- Tujuan Pencairan -->
                    <div>
                        <label for="payment_info" class="block text-xs font-semibold text-gray-700 mb-1">
                            Tujuan Pencairan (Nama Bank / E-Wallet & Nomor Rekening / HP) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="payment_info" name="payment_info" value="{{ old('payment_info') }}" required
                               placeholder="Contoh: BCA 1234567890 a/n Budi Santoso atau DANA 08123456789"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label for="notes" class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea id="notes" name="notes" rows="2"
                                  placeholder="Catatan tambahan untuk admin (opsional)"
                                  class="w-full px-4 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3 px-4 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow transition">
                        Ajukan Klaim Pencairan Rupiah
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-xs overflow-hidden">
        <div class="p-6 border-b border-[#E5E7EB]">
            <h2 class="font-heading font-bold text-lg text-[#1F2937]">Riwayat Penukaran Poin</h2>
            <p class="text-xs text-gray-500">Daftar permintaan pencairan poin ke Rupiah yang pernah Anda ajukan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 font-semibold border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-4">ID / Tanggal</th>
                        <th class="px-6 py-4">Poin Ditukar</th>
                        <th class="px-6 py-4">Nilai Rupiah</th>
                        <th class="px-6 py-4">Tujuan Pencairan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($redemptions as $item)
                        <tr class="hover:bg-pink-50/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-700">
                                <span class="font-bold text-[#1F2937]">#{{ $item->id }}</span>
                                <span class="block text-[11px] text-gray-400">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ number_format($item->points_used, 0, ',', '.') }} Poin
                            </td>
                            <td class="px-6 py-4 font-heading font-bold text-emerald-600">
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
                                        Menunggu Proses
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-[#EF4444] border border-red-200">
                                        Ditolak (Poin Dikembalikan)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div>{{ $item->notes ?? '-' }}</div>
                                @if($item->receipt_image)
                                    <div class="mt-1.5">
                                        <a href="{{ $item->receipt_url }}" target="_blank" class="inline-flex items-center space-x-1 text-[11px] font-semibold text-[#EC4899] hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat Bukti Transfer</span>
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-xs">
                                Belum ada riwayat penukaran poin ke Rupiah.
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pointsInput = document.getElementById('points_used');
        const btnAll = document.getElementById('btn_all_points');
        const calcP20 = document.getElementById('calc_p20');
        const calcSub20 = document.getElementById('calc_sub20');
        const calcP10 = document.getElementById('calc_p10');
        const calcSub10 = document.getElementById('calc_sub10');
        const calcP1 = document.getElementById('calc_p1');
        const calcSub1 = document.getElementById('calc_sub1');
        const calcTotal = document.getElementById('calc_total');

        if (!pointsInput) return;

        function updateGreedyCalculation() {
            const pts = Math.max(0, parseInt(pointsInput.value) || 0);

            // Greedy calculation
            const p20 = Math.floor(pts / 20);
            const rem20 = pts % 20;

            const p10 = Math.floor(rem20 / 10);
            const rem10 = rem20 % 10;

            const p1 = rem10;

            const sub20 = p20 * 1000;
            const sub10 = p10 * 500;
            const sub1 = p1 * 100;
            const total = sub20 + sub10 + sub1;

            calcP20.textContent = p20 + 'x';
            calcSub20.textContent = 'Rp ' + sub20.toLocaleString('id-ID');

            calcP10.textContent = p10 + 'x';
            calcSub10.textContent = 'Rp ' + sub10.toLocaleString('id-ID');

            calcP1.textContent = p1 + 'x';
            calcSub1.textContent = 'Rp ' + sub1.toLocaleString('id-ID');

            calcTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        pointsInput.addEventListener('input', updateGreedyCalculation);
        if (btnAll) {
            btnAll.addEventListener('click', function () {
                pointsInput.value = {{ $user->total_points }};
                updateGreedyCalculation();
            });
        }

        updateGreedyCalculation();
    });
</script>
@endpush
@endsection
