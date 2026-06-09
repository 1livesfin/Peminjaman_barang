@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tags text-indigo-600"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Tambah Kategori Baru</h2>
                    <p class="text-sm text-gray-500">Buat kategori untuk mengelompokkan barang inventaris</p>
                </div>
            </div>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @if($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 rounded-xl">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik, Multimedia..."
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat tentang kategori ini..."
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Font Awesome)</label>
                    <input type="text" name="icon" value="{{ old('icon', 'fa-box') }}" placeholder="fa-box"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-400">Contoh: fa-laptop, fa-camera</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Warna</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="color" value="{{ old('color', '#4F46E5') }}" class="h-10 w-16 rounded-xl border border-gray-200 dark:border-gray-600 cursor-pointer bg-transparent">
                        <input type="text" id="colorText" value="{{ old('color', '#4F46E5') }}" readonly
                               class="flex-1 px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white font-mono">
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan kategori ini</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    document.querySelector('input[type="color"]').addEventListener('input', function() {
        document.getElementById('colorText').value = this.value;
    });
</script>
@endpush
@endsection
