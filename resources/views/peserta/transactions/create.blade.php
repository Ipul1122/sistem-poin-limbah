@extends('layouts.app')

@section('title', 'Setor Limbah')
@section('page_title', 'Form Setoran Limbah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-[#FCE7F3] shadow-xs p-6 md:p-8">
        <div class="border-b border-[#FCE7F3] pb-5 mb-6">
            <h1 class="font-heading font-bold text-xl text-[#1F2937]">Ajukan Penukaran Limbah Baru</h1>
            <p class="text-xs text-gray-500 mt-1">
                Pilih jenis limbah (Botol atau Kaleng), masukkan jumlah/berat, dan lampirkan foto fisik limbah untuk dinilai oleh admin.
            </p>
        </div>

        <form action="{{ route('peserta.transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Kategori Limbah (Strict Botol & Kaleng) -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Pilih Jenis Limbah <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($categories as $category)
                        <label class="relative flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-150 category-card hover:border-[#EC4899] {{ old('category_id') == $category->id ? 'border-[#EC4899] bg-pink-50/30' : 'border-gray-200' }}">
                            <input type="radio" name="category_id" value="{{ $category->id }}"
                                   data-name="{{ $category->name }}"
                                   data-unit="{{ $category->unit_type }}"
                                   data-divisor="{{ $category->unit_divisor }}"
                                   {{ (old('category_id', $loop->first ? $category->id : null) == $category->id) ? 'checked' : '' }}
                                   class="sr-only category-radio">

                            <div class="flex items-center space-x-3 w-full">
                                <div class="w-10 h-10 rounded-xl bg-pink-100 text-[#EC4899] flex items-center justify-center shrink-0">
                                    @if($category->name === 'Botol')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <span class="block font-heading font-bold text-sm text-[#1F2937]">{{ $category->name }}</span>
                                    <span class="block text-xs text-gray-500">
                                        Satuan: <strong>{{ strtoupper($category->unit_type) }}</strong>
                                        ({{ $category->name === 'Botol' ? '1 Poin / Botol' : '1 Poin / 100 gram' }})
                                    </span>
                                </div>
                                <div class="check-icon w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#EC4899] check-dot hidden"></div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Input Jumlah / Berat -->
            <div>
                <label for="quantity_or_weight" id="quantity_label" class="block text-xs font-semibold text-gray-700 mb-1">
                    Jumlah / Berat Limbah <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" id="quantity_or_weight" name="quantity_or_weight"
                           value="{{ old('quantity_or_weight') }}" required min="1" step="1"
                           placeholder="Contoh: 15"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                    <span id="unit_badge" class="absolute right-3 top-2.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-600">
                        pcs
                    </span>
                </div>
                <p id="helper_text" class="text-[11px] text-gray-500 mt-1">
                    Masukkan jumlah botol dalam satuan pcs.
                </p>
            </div>

            <!-- Upload Foto Fisik Limbah -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Foto Bukti Fisik Limbah <span class="text-gray-400 font-normal">(Opsional, Disarankan untuk Grade A/B)</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-pink-200 rounded-2xl bg-pink-50/20 hover:bg-pink-50/40 transition cursor-pointer relative" id="dropzone">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)">

                    <div class="text-center" id="upload_prompt">
                        <svg class="mx-auto h-10 w-10 text-pink-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-1 text-xs text-gray-600">
                            <span class="font-semibold text-[#EC4899]">Klik untuk pilih foto</span> atau tarik file ke sini
                        </p>
                        <p class="text-[10px] text-gray-400 mt-0.5">PNG, JPG, WEBP hingga 2MB</p>
                    </div>

                    <!-- Image Preview Area -->
                    <div id="image_preview_container" class="hidden text-center z-20">
                        <img id="image_preview" src="#" alt="Preview Foto Limbah" class="max-h-48 rounded-xl shadow-md mx-auto object-cover border border-pink-200 mb-2">
                        <button type="button" onclick="clearImage(event)" class="text-[11px] font-semibold text-red-600 hover:underline">
                            Hapus & Ganti Foto
                        </button>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="notes" class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                <textarea id="notes" name="notes" rows="2"
                          placeholder="Contoh: Botol plastik air mineral 600ml kondisi kering dan bersih."
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">{{ old('notes') }}</textarea>
            </div>

            <!-- Informational Banner -->
            <div class="p-4 rounded-xl bg-pink-50/50 border border-pink-200 text-xs text-pink-950 flex items-start space-x-3">
                <svg class="w-5 h-5 text-[#EC4899] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-semibold">Informasi:</span> Melampirkan foto limbah yang jelas akan membantu Administrator dalam menetapkan <strong>Grade A (+4 Poin)</strong> atau <strong>Grade B (+3 Poin)</strong> secara akurat.
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-200">
                <a href="{{ route('peserta.transactions.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow transition">
                    Kirim Setoran Limbah
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_preview').src = e.target.result;
                document.getElementById('image_preview_container').classList.remove('hidden');
                document.getElementById('upload_prompt').classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function clearImage(e) {
        e.stopPropagation();
        const input = document.getElementById('image');
        input.value = '';
        document.getElementById('image_preview').src = '#';
        document.getElementById('image_preview_container').classList.add('hidden');
        document.getElementById('upload_prompt').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.category-radio');
        const label = document.getElementById('quantity_label');
        const unitBadge = document.getElementById('unit_badge');
        const helperText = document.getElementById('helper_text');
        const input = document.getElementById('quantity_or_weight');

        function updateUI() {
            radios.forEach(r => {
                const card = r.closest('.category-card');
                const dot = card.querySelector('.check-dot');
                if (r.checked) {
                    card.classList.add('border-[#EC4899]', 'bg-pink-50/30');
                    card.classList.remove('border-gray-200');
                    dot.classList.remove('hidden');

                    const name = r.dataset.name;
                    const unit = r.dataset.unit;
                    unitBadge.textContent = unit;

                    if (name === 'Kaleng') {
                        label.innerHTML = 'Berat Kaleng <span class="text-red-500">*</span>';
                        input.min = '100';
                        input.placeholder = 'Contoh: 500 (gram)';
                        helperText.textContent = 'Minimal 100 gram. Setiap 100 gram kaleng bernilai 1 Poin Dasar.';
                    } else {
                        label.innerHTML = 'Jumlah Botol <span class="text-red-500">*</span>';
                        input.min = '1';
                        input.placeholder = 'Contoh: 15 (pcs)';
                        helperText.textContent = 'Minimal 1 pcs. Setiap 1 botol bernilai 1 Poin Dasar.';
                    }
                } else {
                    card.classList.remove('border-[#EC4899]', 'bg-pink-50/30');
                    card.classList.add('border-gray-200');
                    dot.classList.add('hidden');
                }
            });
        }

        radios.forEach(r => r.addEventListener('change', updateUI));
        updateUI();
    });
</script>
@endpush
@endsection
