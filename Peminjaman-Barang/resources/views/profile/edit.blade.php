@extends('layouts.app')
@section('title', 'Profil Saya')
@section('header', 'Profil Saya')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Profile Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="h-28 bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-500"></div>
        <div class="px-6 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-12 mb-5">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-2xl ring-4 ring-white dark:ring-gray-800 shadow-xl object-cover">
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full flex items-center justify-center bg-green-500 ring-2 ring-white dark:ring-gray-800">
                        <i class="fas fa-check text-white" style="font-size:8px"></i>
                    </span>
                </div>
                <span class="mt-4 sm:mt-0 px-3 py-1.5 text-xs font-bold rounded-xl {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            @if($user->department)
                <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1"><i class="fas fa-building mr-1"></i>{{ $user->department }}</p>
            @endif
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white"><i class="fas fa-user-edit mr-2 text-indigo-500"></i>Edit Profil</h3>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf @method('PATCH')

            @if($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 rounded-xl">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li class="text-sm text-red-600">{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen</label>
                    <input type="text" name="department" value="{{ old('department', $user->department) }}"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Profil</label>
                <div x-data="{ preview: '{{ $user->avatar_url }}' }" class="flex items-center space-x-4">
                    <img :src="preview" class="w-16 h-16 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                    <div>
                        <label for="avatarInput" class="cursor-pointer inline-flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <i class="fas fa-upload text-xs"></i><span>Pilih Foto</span>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP max 2MB</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white"><i class="fas fa-lock mr-2 text-amber-500"></i>Ganti Password</h3>
        </div>
        <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Saat Ini</label>
                <input type="password" name="current_password"
                       class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex justify-end pt-2 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 transition-all">
                    <i class="fas fa-key mr-2"></i>Perbarui Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
