@extends('layouts.app')

@section('title', 'Verifikasi Transaksi #' . $transaction->id)
@section('page_title', 'Detail & Verifikasi Transaksi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-gray-500 hover:text-[#EC4899] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Transaksi</span>
        </a>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-2xl border border-[#FCE7F3] shadow-xs overflow-hidden">
        <div class="p-6 md:p-8 border-b border-[#FCE7F3] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="font-heading font-extrabold text-2xl text-[#1F2937]">Transaksi #{{ $transaction->id }}</h1>
                    @if($transaction->status === 'approved')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Disetujui
                        </span>
                    @elseif($transaction->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                            Menunggu Verifikasi
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                            Ditolak
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-1">Diajukan pada {{ $transaction->created_at->format('d F Y, H:i:s') }}</p>
            </div>

            <!-- Peserta info pill -->
            <div class="bg-gray-50 border border-gray-200 p-3 rounded-xl flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                </div>
                <div class="text-xs">
                    <span class="font-bold text-gray-800 block">{{ $transaction->user->name }}</span>
                    <span class="text-gray-500">{{ $transaction->user->email }}</span>
                    <span class="text-[#EC4899] font-semibold block">Saldo: {{ $transaction->user->total_points }} Poin</span>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            <!-- Waste Overview Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <span class="block text-gray-400 text-xs font-medium">Kategori Limbah</span>
                    <span class="font-heading font-bold text-base text-gray-800 mt-1 block">{{ $transaction->category->name }}</span>
                    <span class="text-[11px] text-gray-500">Satuan: {{ strtoupper($transaction->category->unit_type) }}</span>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <span class="block text-gray-400 text-xs font-medium">Jumlah / Berat Yang Disetor</span>
                    <span class="font-heading font-bold text-base text-gray-800 mt-1 block">
                        {{ number_format($transaction->quantity_or_weight, 0, ',', '.') }} {{ $transaction->category->unit_type }}
                    </span>
                    <span class="text-[11px] text-gray-500">
                        @if($transaction->category->name === 'Botol')
                            1 Poin per 1 botol
                        @else
                            1 Poin per 100 gram
                        @endif
                    </span>
                </div>

                @php
                    $calcService = app(\App\Services\PointCalculationService::class);
                    $basePoints = $calcService->calculateBasePoints($transaction->category, $transaction->quantity_or_weight);
                @endphp
                <div class="bg-pink-50/50 p-4 rounded-xl border border-pink-200">
                    <span class="block text-pink-600 text-xs font-medium">Kalkulasi Poin Dasar (Base)</span>
                    <span class="font-heading font-extrabold text-2xl text-[#EC4899] mt-1 block">
                        {{ $basePoints }} Poin
                    </span>
                    <span class="text-[11px] text-gray-500">Sebelum bonus grade kelayakan</span>
                </div>
            </div>

            <!-- Foto Fisik Limbah Section -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200">
                <span class="block text-xs font-semibold text-gray-700 mb-2">Foto Fisik Limbah dari Peserta:</span>
                @if($transaction->image_path)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <a href="{{ $transaction->image_url }}" target="_blank" class="block group relative overflow-hidden rounded-xl border-2 border-pink-300 shadow-sm max-w-xs">
                            <img src="{{ $transaction->image_url }}" alt="Foto Limbah #{{ $transaction->id }}" class="h-44 w-auto object-cover group-hover:scale-105 transition duration-200">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-medium transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Perbesar Foto
                            </div>
                        </a>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p class="font-semibold text-gray-800">Pemeriksaan Kondisi Fisik:</p>
                            <p class="text-gray-500">Perhatikan kebersihan, tutup/label botol, atau kondisi penyok pada kaleng untuk menentukan Grade kelayakan yang objektif.</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-white border border-dashed border-gray-300 text-center text-xs text-gray-400">
                        <svg class="w-8 h-8 mx-auto text-gray-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Peserta tidak melampirkan foto fisik pada setoran ini.</span>
                    </div>
                @endif
            </div>

            <!-- Notes by participant -->
            <div>
                <span class="block text-xs font-semibold text-gray-700 mb-1">Catatan dari Peserta:</span>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs text-gray-700">
                    {{ $transaction->notes ?: 'Tidak ada catatan khusus dari peserta.' }}
                </div>
            </div>

            @if($transaction->status !== 'pending')
                <!-- Verification Result -->
                <div class="p-6 rounded-2xl bg-gray-50 border border-gray-200 space-y-4">
                    <h3 class="font-heading font-bold text-sm text-gray-800">Hasil Verifikasi</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div>
                            <span class="text-gray-400 block">Grade Kelayakan:</span>
                            @if($transaction->grade)
                                <span class="font-bold text-gray-800 text-sm">Grade {{ $transaction->grade->grade_code }} ({{ $transaction->grade->label }} +{{ $transaction->grade->bonus_point }} Poin)</span>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-gray-400 block">Total Poin Diberikan:</span>
                            <span class="font-heading font-extrabold text-base {{ $transaction->status === 'approved' ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $transaction->earned_points }} Poin
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block">Diverifikasi Oleh:</span>
                            <span class="font-semibold text-gray-800">{{ $transaction->verifier?->name ?? 'Admin' }}</span>
                            <span class="block text-[11px] text-gray-400">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Verification Form for Admin -->
                <div class="border-t border-[#FCE7F3] pt-6">
                    <h2 class="font-heading font-bold text-lg text-[#1F2937] mb-2">Formulir Verifikasi & Penilaian Kelayakan</h2>
                    <p class="text-xs text-gray-500 mb-6">
                        Pilih Grade kelayakan fisik limbah yang disetor untuk menentukan bonus poin, atau tolak jika tidak sesuai syarat.
                    </p>

                    <form action="{{ route('admin.transactions.verify', $transaction) }}" method="POST" id="verify_form" class="space-y-6">
                        @csrf

                        <!-- Grade Selection -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Tentukan Grade Kelayakan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($grades as $gr)
                                    <label class="p-4 rounded-xl border-2 cursor-pointer transition grade-card hover:border-[#EC4899] {{ $loop->first ? 'border-[#EC4899] bg-pink-50/40' : 'border-gray-200' }}">
                                        <input type="radio" name="grade_id" value="{{ $gr->id }}"
                                               data-code="{{ $gr->grade_code }}"
                                               data-bonus="{{ $gr->bonus_point }}"
                                               {{ $loop->first ? 'checked' : '' }}
                                               class="sr-only grade-radio">
                                        <div class="text-center">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold mb-1
                                                {{ $gr->grade_code === 'A' ? 'bg-emerald-100 text-emerald-800' :
                                                   ($gr->grade_code === 'B' ? 'bg-blue-100 text-blue-800' :
                                                   ($gr->grade_code === 'C' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')) }}">
                                                Grade {{ $gr->grade_code }}
                                            </span>
                                            <span class="block font-semibold text-xs text-gray-800">{{ $gr->label }}</span>
                                            <span class="block font-heading font-bold text-sm text-[#EC4899] mt-1">+{{ $gr->bonus_point }} Poin</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Live Calculation Preview -->
                        <div class="p-4 rounded-xl bg-pink-50/50 border border-pink-200 text-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <span class="text-gray-500">Formula Kalkulasi Poin:</span>
                                <div class="font-medium text-gray-800 mt-0.5">
                                    Base Point (<span id="calc_base">{{ $basePoints }}</span>) + Bonus Grade (<span id="calc_bonus">4</span>)
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500">Total Poin yang Akan Diterima:</span>
                                <div class="font-heading font-extrabold text-2xl text-[#EC4899]">
                                    <span id="calc_total">{{ $basePoints + 4 }}</span> Poin
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Verifikasi -->
                        <div>
                            <label for="notes" class="block text-xs font-semibold text-gray-700 mb-1">Catatan Verifikasi (Wajib jika menolak setoran)</label>
                            <textarea id="notes" name="notes" rows="2"
                                      placeholder="Contoh: Kondisi botol bersih, label telah dilepas. / Alasan penolakan jika ditolak."
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button type="submit" name="action" value="reject"
                                    onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini? Pastikan Anda telah mengisi alasan pada kolom catatan.');"
                                    class="px-5 py-2.5 rounded-xl border border-red-300 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                Tolak Transaksi (0 Poin)
                            </button>

                            <button type="submit" name="action" value="approve"
                                    class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow transition">
                                Setujui & Tambah Poin Saldo Peserta
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.grade-radio');
        const basePoints = {{ $basePoints }};
        const calcBonus = document.getElementById('calc_bonus');
        const calcTotal = document.getElementById('calc_total');

        function updateGradeSelection() {
            radios.forEach(r => {
                const card = r.closest('.grade-card');
                if (r.checked) {
                    card.classList.add('border-[#EC4899]', 'bg-pink-50/40');
                    card.classList.remove('border-gray-200');

                    const bonus = parseInt(r.dataset.bonus) || 0;
                    if (calcBonus && calcTotal) {
                        calcBonus.textContent = bonus;
                        calcTotal.textContent = basePoints + bonus;
                    }
                } else {
                    card.classList.remove('border-[#EC4899]', 'bg-pink-50/40');
                    card.classList.add('border-gray-200');
                }
            });
        }

        radios.forEach(r => r.addEventListener('change', updateGradeSelection));
        updateGradeSelection();
    });
</script>
@endpush
@endsection
