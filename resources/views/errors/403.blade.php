<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | EcoPoint</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF5F7] text-[#1F2937] min-h-screen flex items-center justify-center p-4 font-['Poppins']">

    <div class="max-w-md w-full bg-white rounded-2xl border border-[#E5E7EB] shadow-xl p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 bg-red-100 text-[#EF4444] rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3.5a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <span class="font-heading font-extrabold text-4xl text-[#EF4444] block mb-1">403</span>
        <h1 class="font-heading font-bold text-xl text-[#1F2937] mb-2">Akses Ditolak</h1>
        <p class="text-xs text-gray-500 mb-6">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman ini.' }}
        </p>

        <div class="space-y-2">
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-block w-full py-2.5 px-4 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] shadow hover:opacity-95 transition">
                    Kembali ke Dashboard Admin
                </a>
            @elseif(Auth::check() && Auth::user()->isPeserta())
                <a href="{{ route('peserta.dashboard') }}"
                   class="inline-block w-full py-2.5 px-4 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] shadow hover:opacity-95 transition">
                    Kembali ke Dashboard Peserta
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-block w-full py-2.5 px-4 rounded-xl font-heading font-bold text-xs text-white bg-gradient-to-r from-[#F472B6] to-[#FB7185] shadow hover:opacity-95 transition">
                    Halaman Login
                </a>
            @endif
        </div>
    </div>

</body>
</html>
