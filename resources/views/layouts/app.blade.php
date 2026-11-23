<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EcoPoint') - Sistem Penukaran Poin Limbah</title>

    <!-- Google Fonts: Montserrat (Heading) & Poppins (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Heroicons / FontAwesome alternative (SVG inline or CDN) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF5F7] text-[#1F2937] min-h-screen flex flex-col font-['Poppins']">

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-[#E5E7EB] flex flex-col shrink-0 hidden md:flex">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 border-b border-[#E5E7EB]">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#F472B6] to-[#FB7185] flex items-center justify-center text-white shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-heading font-bold text-lg text-[#1F2937] tracking-tight">Eco<span class="text-[#EC4899]">Point</span></span>
                        <span class="block text-[10px] text-gray-400 font-medium tracking-wider uppercase">Waste To Point</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                @if(Auth::check() && Auth::user()->isAdmin())
                    <!-- Admin Menu -->
                    <div class="px-3 pb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                        Menu Administrator
                    </div>

                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.transactions.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span>Verifikasi Limbah</span>
                        </div>
                        @php
                            $pendingTxCount = \App\Models\WasteTransaction::where('status', 'pending')->count();
                        @endphp
                        @if($pendingTxCount > 0)
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ request()->routeIs('admin.transactions.*') ? 'bg-white text-[#EC4899]' : 'bg-[#F59E0B] text-white' }}">{{ $pendingTxCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.redemptions.index') }}"
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.redemptions.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Penukaran Poin</span>
                        </div>
                        @php
                            $pendingRedCount = \App\Models\PointRedemption::where('status', 'pending')->count();
                        @endphp
                        @if($pendingRedCount > 0)
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ request()->routeIs('admin.redemptions.*') ? 'bg-white text-[#EC4899]' : 'bg-red-500 text-white' }}">{{ $pendingRedCount }}</span>
                        @endif
                    </a>

                    <div class="pt-4 px-3 pb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                        Master Data & Parameter
                    </div>

                    <a href="{{ route('admin.categories.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Kategori Limbah</span>
                    </a>

                    <a href="{{ route('admin.grades.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.grades.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span>Grade Kelayakan</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Data Peserta</span>
                    </a>
                @elseif(Auth::check() && Auth::user()->isPeserta())
                    <!-- Peserta Menu -->
                    <div class="px-3 pb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                        Menu Peserta
                    </div>

                    <a href="{{ route('peserta.dashboard') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('peserta.dashboard') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('peserta.transactions.create') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('peserta.transactions.create') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Setor Limbah</span>
                    </a>

                    <a href="{{ route('peserta.transactions.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('peserta.transactions.index') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Setoran</span>
                    </a>

                    <a href="{{ route('peserta.redemptions.index') }}"
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ request()->routeIs('peserta.redemptions.*') ? 'bg-gradient-to-r from-[#F472B6] to-[#FB7185] text-white shadow-sm' : 'text-gray-600 hover:bg-pink-50 hover:text-[#EC4899]' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Tukar Poin ke Rupiah</span>
                    </a>
                @endif
            </div>

            <!-- User Info & Logout Button -->
            <div class="p-4 border-t border-[#E5E7EB] bg-[#FFF5F7]">
                @if(Auth::check())
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#1F2937] truncate">{{ Auth::user()->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar (Logout)</span>
                        </button>
                    </form>
                @endif
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar (Mobile toggle & notifications) -->
            <header class="h-16 bg-white border-b border-[#E5E7EB] px-4 md:px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center space-x-3">
                    <span class="font-heading font-bold text-lg md:text-xl text-[#1F2937]">@yield('page_title', 'EcoPoint')</span>
                </div>

                <div class="flex items-center space-x-4">
                    @if(Auth::check() && Auth::user()->isPeserta())
                        <div class="flex items-center space-x-2 bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 px-3.5 py-1.5 rounded-full">
                            <span class="text-xs text-gray-600 font-medium">Saldo Poin:</span>
                            <span class="font-heading font-bold text-sm text-[#EC4899]">{{ number_format(Auth::user()->total_points, 0, ',', '.') }}</span>
                            <span class="text-[11px] text-gray-500">Poin</span>
                        </div>
                    @endif

                    @if(Auth::check())
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                    @endif
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                <!-- Alerts / Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start space-x-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#10B981] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1 text-sm text-emerald-800 font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start space-x-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#EF4444] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1 text-sm text-red-800 font-medium">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start space-x-3 shadow-xs">
                        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1 text-sm text-amber-800 font-medium">
                            {{ session('warning') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 shadow-xs">
                        <div class="flex items-center space-x-2 text-sm text-red-800 font-semibold mb-1">
                            <svg class="w-5 h-5 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Harap periksa kesalahan input berikut:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-[#E5E7EB] py-4 px-8 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} EcoPoint Monolith — Sistem Penukaran Poin Limbah Berkelanjutan.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
