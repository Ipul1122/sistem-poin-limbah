<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta - EcoPoint Monolith</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF5F7] text-[#1F2937] min-h-screen flex items-center justify-center p-4 font-['Poppins']">

    <div class="max-w-md w-full bg-white rounded-2xl border border-[#E5E7EB] shadow-xl overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-[#F472B6] to-[#FB7185] p-6 text-center text-white">
            <div class="w-14 h-14 mx-auto mb-3 bg-white/20 backdrop-blur-xs rounded-2xl flex items-center justify-center border border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="font-heading font-bold text-2xl tracking-tight">EcoPoint System</h1>
            <p class="text-pink-100 text-xs mt-1">Registrasi Akun Peserta Baru</p>
        </div>

        <div class="p-8">
            <h2 class="font-heading font-bold text-xl text-[#1F2937] mb-2 text-center">Buat Akun Peserta</h2>
            <p class="text-xs text-gray-500 mb-6 text-center">Mulai setor limbah botol & kaleng untuk kumpulkan poin Rupiah</p>

            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi kata sandi"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3 px-4 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow-md hover:shadow-lg transition-all duration-150">
                        Daftar Sebagai Peserta
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-gray-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-[#EC4899] hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>

</body>
</html>
