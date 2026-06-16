<div class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden sm:flex flex-col shadow-lg">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-700 bg-emerald-600 dark:bg-emerald-800">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white flex items-center gap-2">
            <i data-lucide="box" class="w-6 h-6"></i>
            InventoryApp
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full mb-2">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            {{ __('Dashboard') }}
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
            <div class="mt-2 space-y-1">
                <a href="{{ route('items.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('items.*') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="package" class="w-5 h-5"></i> Barang
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('categories.*') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="tags" class="w-5 h-5"></i> Kategori
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="users" class="w-5 h-5"></i> Pengguna
                </a>
            </div>
        </div>
        @endif

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi</p>
            <div class="mt-2 space-y-1">
                <a href="{{ route('loans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('loans.*') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i> Peminjaman
                </a>
                <a href="{{ route('returns.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('returns.*') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="arrow-down-left" class="w-5 h-5"></i> Pengembalian
                </a>
            </div>
        </div>

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Laporan</p>
            <div class="mt-2 space-y-1">
                <a href="{{ route('reports.loans') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports.loans') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="file-text" class="w-5 h-5"></i> Laporan Peminjaman
                </a>
                <a href="{{ route('reports.returns') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports.returns') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="file-check-2" class="w-5 h-5"></i> Laporan Pengembalian
                </a>
                <a href="{{ route('reports.stock') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('reports.stock') ? 'bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/50' }} w-full">
                    <i data-lucide="bar-chart-2" class="w-5 h-5"></i> Laporan Stok Barang
                </a>
            </div>
        </div>
    </nav>
</div>
