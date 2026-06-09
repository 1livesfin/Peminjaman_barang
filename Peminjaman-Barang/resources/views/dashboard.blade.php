@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fas fa-boxes-stacked text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <span class="text-xs font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg">Total</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($totalItems) }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Barang</p>
        </div>
    </div>

    <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-1 rounded-lg">Siap</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($availableItems) }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Barang Tersedia</p>
        </div>
    </div>

    <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 rounded-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <i class="fas fa-hand-holding text-amber-600 dark:text-amber-400"></i>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded-lg">Aktif</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($borrowedItems) }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
        </div>
    </div>

    <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-cyan-600 dark:text-cyan-400"></i>
                </div>
                <span class="text-xs font-medium text-cyan-600 bg-cyan-100 dark:bg-cyan-900/30 px-2 py-1 rounded-lg">All Time</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($totalBorrowings) }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</p>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-4 text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $totalUsers }}</div>
                <p class="text-indigo-200 text-sm">Total Pengguna</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-4 text-white shadow-lg shadow-amber-200 dark:shadow-amber-900/30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $pendingBorrowings }}</div>
                <p class="text-orange-200 text-sm">Menunggu Approval</p>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-rose-500 rounded-2xl p-4 text-white shadow-lg shadow-red-200 dark:shadow-red-900/30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="text-2xl font-bold">{{ $overdueBorrowings }}</div>
                <p class="text-red-200 text-sm">Terlambat Kembali</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Quick Actions</p>
        <div class="space-y-2">
            <a href="{{ route('borrowings.create') }}" class="flex items-center space-x-2 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium group">
                <i class="fas fa-plus-circle text-xs group-hover:scale-125 transition-transform"></i>
                <span>Ajukan Peminjaman</span>
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('items.create') }}" class="flex items-center space-x-2 text-sm text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 font-medium group">
                <i class="fas fa-box-open text-xs group-hover:scale-125 transition-transform"></i>
                <span>Tambah Barang</span>
            </a>
            <a href="{{ route('borrowings.index') }}" class="flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 hover:text-amber-800 font-medium group">
                <i class="fas fa-clipboard-check text-xs group-hover:scale-125 transition-transform"></i>
                <span>Approve Peminjaman</span>
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Charts + Popular -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Grafik Peminjaman Bulanan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">6 bulan terakhir</p>
            </div>
        </div>
        <div class="h-48 flex items-end gap-2 px-2">
            @php $maxCount = max(array_column($monthlyData, 'count') ?: [1]); @endphp
            @foreach($monthlyData as $data)
                @php $height = $maxCount > 0 ? max(8, ($data['count'] / $maxCount) * 100) : 8; @endphp
                <div class="flex-1 flex flex-col items-center gap-1 group">
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity">{{ $data['count'] }}</span>
                    <div class="w-full rounded-t-lg bg-gradient-to-t from-indigo-600 to-cyan-400 transition-all duration-700 hover:opacity-80 cursor-pointer"
                         style="height: {{ $height }}%"
                         title="{{ $data['month'] }}: {{ $data['count'] }} peminjaman"></div>
                    <span class="text-xs text-gray-400 dark:text-gray-500 text-center whitespace-nowrap">{{ $data['month'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Barang Terpopuler</h3>
        <div class="space-y-4">
            @forelse($popularItems as $index => $item)
                @php $maxBorrow = $popularItems->max('borrow_count') ?: 1; $pct = ($item->borrow_count / $maxBorrow) * 100; @endphp
                <div class="flex items-center space-x-3">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-indigo-50 text-indigo-600')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item->name }}</p>
                        <div class="mt-1 w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-400" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $item->borrow_count }}x</span>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fas fa-chart-bar text-gray-300 text-3xl mb-2"></i>
                    <p class="text-sm text-gray-400">Belum ada data peminjaman</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Borrowings + Activity -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Peminjaman Terbaru</h3>
            <a href="{{ route('borrowings.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            @forelse($recentBorrowings as $borrowing)
                @php $badge = $borrowing->status_badge; @endphp
                <a href="{{ route('borrowings.show', $borrowing) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-indigo-600 dark:text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $borrowing->borrowing_number }} · {{ $borrowing->borrow_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </a>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-clipboard text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada peminjaman</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            @forelse($recentActivities as $activity)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center mt-0.5
                            {{ $activity->action === 'create' ? 'bg-green-100 dark:bg-green-900/30' : ($activity->action === 'delete' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30') }}">
                            <i class="fas {{ $activity->action === 'create' ? 'fa-plus text-green-600' : ($activity->action === 'delete' ? 'fa-trash text-red-600' : 'fa-edit text-blue-600') }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug">{{ $activity->description }}</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $activity->user?->name ?? 'System' }}</p>
                                <span class="text-gray-300">·</span>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
