@extends('layouts.app')

@section('title', 'Manajemen Barang')
@section('header', 'Manajemen Barang')

@section('content')
<!-- Header Actions -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola inventaris barang yang dapat dipinjam</p>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('items.create') }}"
       class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
        <i class="fas fa-plus text-xs"></i>
        <span>Tambah Barang</span>
    </a>
    @endif
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 shadow-sm">
    <form action="{{ route('items.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau kode barang..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
        </div>
        <select name="category" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="tidak_tersedia" {{ request('status') === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
            <option value="perbaikan" {{ request('status') === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
            <i class="fas fa-filter mr-1"></i> Filter
        </button>
        @if(request()->hasAny(['search','category','status']))
        <a href="{{ route('items.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
            Reset
        </a>
        @endif
    </form>
</div>

<!-- Items Grid -->
@if($items->isEmpty())
    <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-boxes-stacked text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada barang ditemukan</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm">Coba ubah filter pencarian atau tambah barang baru</p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('items.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Tambah Barang</span>
        </a>
        @endif
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        @foreach($items as $item)
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
            <!-- Image -->
            <div class="relative h-44 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 overflow-hidden">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-box text-gray-300 dark:text-gray-500 text-4xl"></i>
                    </div>
                @endif
                <!-- Status badge -->
                <div class="absolute top-2 right-2">
                    @php
                        $statusMap = [
                            'tersedia' => 'bg-green-500',
                            'dipinjam' => 'bg-amber-500',
                            'tidak_tersedia' => 'bg-red-500',
                            'perbaikan' => 'bg-blue-500',
                        ];
                        $statusLabel = [
                            'tersedia' => 'Tersedia',
                            'dipinjam' => 'Dipinjam',
                            'tidak_tersedia' => 'N/A',
                            'perbaikan' => 'Perbaikan',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold text-white {{ $statusMap[$item->status] ?? 'bg-gray-500' }}">
                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1 animate-pulse"></span>
                        {{ $statusLabel[$item->status] ?? $item->status }}
                    </span>
                </div>
                <!-- Category -->
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-0.5 bg-black/30 backdrop-blur-sm text-white text-xs rounded-full">{{ $item->category->name }}</span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <div class="mb-3">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-mono mb-1">{{ $item->code }}</p>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-snug">{{ $item->name }}</h3>
                    @if($item->brand)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $item->brand }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-1">
                        <i class="fas fa-layer-group text-gray-400 text-xs"></i>
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ $item->stock_available }}/{{ $item->stock }} tersedia</span>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $item->condition === 'baik' ? 'bg-green-100 text-green-700' : ($item->condition === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ $item->condition_label }}
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('items.show', $item) }}" class="flex-1 text-center py-2 text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('items.edit', $item) }}" class="flex-1 text-center py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-xs text-red-500 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $items->links() }}
    </div>
@endif
@endsection
