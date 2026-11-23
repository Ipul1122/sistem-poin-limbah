<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - EcoPoint Monolith</title>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <h1 class="font-heading font-bold text-2xl tracking-tight">EcoPoint System</h1>
            <p class="text-pink-100 text-xs mt-1">Tukarkan Limbah Botol & Kaleng Anda Menjadi Rupiah</p>
        </div>

        <div class="p-8">
            <h2 class="font-heading font-bold text-xl text-[#1F2937] mb-2 text-center">Masuk ke Akun</h2>
            <p class="text-xs text-gray-500 mb-6 text-center">Gunakan kredensial yang terdaftar untuk melanjutkan</p>

            <!-- Flash alerts -->
            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#EC4899] focus:border-transparent transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-[#EC4899] focus:ring-[#EC4899] border-gray-300">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 rounded-xl font-heading font-bold text-sm text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] hover:opacity-95 shadow-md hover:shadow-lg transition-all duration-150">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2 text-center">Akun Demo Cepat</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button"
                            onclick="document.getElementById('email').value='admin@ecopoint.test'; document.getElementById('password').value='admin123';"
                            class="p-2 rounded-lg bg-gray-50 border border-gray-200 hover:bg-pink-50 hover:border-pink-200 text-left transition">
                        <span class="block font-semibold text-gray-800">Admin</span>
                        <span class="text-[10px] text-gray-500">admin@ecopoint.test</span>
                    </button>
                    <button type="button"
                            onclick="document.getElementById('email').value='peserta@ecopoint.test'; document.getElementById('password').value='peserta123';"
                            class="p-2 rounded-lg bg-gray-50 border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 text-left transition">
                        <span class="block font-semibold text-gray-800">Peserta</span>
                        <span class="text-[10px] text-gray-500">peserta@ecopoint.test</span>
                    </button>
                </div>
            </div>

            <div class="mt-6 text-center text-xs text-gray-600">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-semibold text-[#EC4899] hover:underline">Daftar sebagai Peserta</a>
            </div>
        </div>
    </div>

</body>
</html>
