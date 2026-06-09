<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - BorrowEase</title>
    <meta name="description" content="Sistem Peminjaman Barang BorrowEase - Kelola peminjaman inventaris dengan mudah dan efisien">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-300">

<div x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar -->
    <aside :class="sidebarCollapsed ? 'w-16' : 'w-64'"
           class="fixed inset-y-0 left-0 z-40 flex flex-col transition-all duration-300 ease-in-out
                  -translate-x-full lg:translate-x-0
                  bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-xl"
           :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-boxes-stacked text-white text-sm"></i>
                </div>
                <span x-show="!sidebarCollapsed" class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-cyan-500 bg-clip-text text-transparent truncate">BorrowEase</span>
            </a>
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-1 rounded text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="fas fa-chevron-left text-xs transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100' }}">
                <i class="fas fa-chart-pie w-5 text-center flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3 truncate">Dashboard</span>
            </a>

            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Inventaris</p>
            </div>

            <a href="{{ route('items.index') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('items.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('items.*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Manajemen Barang</span>
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('categories.index') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('categories.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-tags w-5 text-center flex-shrink-0 {{ request()->routeIs('categories.*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Kategori</span>
            </a>
            @endif

            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Peminjaman</p>
            </div>

            @php $pendingCount = \App\Models\Borrowing::where('status','menunggu')->count(); @endphp
            <a href="{{ route('borrowings.index') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('borrowings.index') || request()->routeIs('borrowings.show') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-clipboard-list w-5 text-center flex-shrink-0 {{ request()->routeIs('borrowings.index') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3 flex-1">Peminjaman</span>
                @if($pendingCount > 0 && auth()->user()->isAdmin())
                    <span x-show="!sidebarCollapsed" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('borrowings.create') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('borrowings.create') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-plus-circle w-5 text-center flex-shrink-0 {{ request()->routeIs('borrowings.create') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Ajukan Peminjaman</span>
            </a>

            @if(auth()->user()->isAdmin())
            <a href="{{ route('returns.index') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('returns.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-undo-alt w-5 text-center flex-shrink-0 {{ request()->routeIs('returns.*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Pengembalian</span>
            </a>
            @endif

            <a href="{{ route('borrowings.history') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('borrowings.history') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-history w-5 text-center flex-shrink-0 {{ request()->routeIs('borrowings.history') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Riwayat</span>
            </a>

            @if(auth()->user()->isAdmin())
            <div x-show="!sidebarCollapsed" class="pt-3 pb-1 px-3">
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Administrasi</p>
            </div>

            <a href="{{ route('users.index') }}"
               class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('users.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-users w-5 text-center flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                <span x-show="!sidebarCollapsed" class="ml-3">Kelola Pengguna</span>
            </a>
            @endif
        </nav>

        <!-- User at bottom -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                     class="w-8 h-8 rounded-full ring-2 ring-indigo-200 dark:ring-indigo-800 flex-shrink-0">
                <div x-show="!sidebarCollapsed" class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate capitalize">{{ auth()->user()->role }}</p>
                </div>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="transition-all duration-300 ease-in-out" :class="sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64'">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-20 h-16 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 lg:px-6">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('header', 'Dashboard')</h1>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Dark mode -->
                <button @click="darkMode = !darkMode"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <i class="fas fa-moon dark:hidden"></i>
                    <i class="fas fa-sun hidden dark:block text-yellow-400"></i>
                </button>

                <!-- User menu -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                             class="w-8 h-8 rounded-full ring-2 ring-indigo-200 dark:ring-indigo-800">
                        <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                        <div class="p-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs rounded-full capitalize">{{ auth()->user()->role }}</span>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <i class="fas fa-user w-4 text-gray-400"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <i class="fas fa-clipboard-list w-4 text-gray-400"></i>
                                <span>Peminjaman Saya</span>
                            </a>
                            <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center space-x-2 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 lg:p-6 min-h-screen">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-2xl">
                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                    <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                    <button @click="show = false" class="text-green-400 hover:text-green-600"><i class="fas fa-times text-xs"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-2xl">
                    <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                    <button @click="show = false" class="text-red-400 hover:text-red-600"><i class="fas fa-times text-xs"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
