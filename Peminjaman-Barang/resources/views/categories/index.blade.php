@extends('layouts.app')
@section('title', 'Kategori')
@section('header', 'Kategori Barang')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori untuk pengelompokan barang</p>
    <a href="{{ route('categories.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 hover:-translate-y-0.5 transition-all">
        <i class="fas fa-plus text-xs"></i><span>Tambah Kategori</span>
    </a>
</div>

<!-- Search -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 shadow-sm">
    <form action="{{ route('categories.index') }}" method="GET" class="flex gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">Cari</button>
    </form>
</div>

<!-- Grid -->
@if($categories->isEmpty())
    <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
        <i class="fas fa-tags text-gray-300 text-4xl mb-3"></i>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada kategori</h3>
        <a href="{{ route('categories.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium mt-4 hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus"></i><span>Tambah Kategori</span>
        </a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        @foreach($categories as $category)
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
            <div class="h-2 w-full" style="background-color: {{ $category->color }}"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-xl" style="background-color: {{ $category->color }}">
                        <i class="fas {{ $category->icon }}"></i>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $category->name }}</h3>
                @if($category->description)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $category->description }}</p>
                @endif
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                    <span><i class="fas fa-box mr-1"></i>{{ $category->items_count }} barang</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="flex-1 text-center py-2 text-xs font-medium text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="py-2 px-3 text-xs text-red-500 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-center">{{ $categories->links() }}</div>
@endif
@endsection
