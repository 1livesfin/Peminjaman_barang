@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun pengguna sistem BorrowEase</p>
    <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 hover:-translate-y-0.5 transition-all">
        <i class="fas fa-user-plus text-xs"></i><span>Tambah Pengguna</span>
    </a>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 shadow-sm">
    <form action="{{ route('users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <select name="role" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    @if($users->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Belum ada pengguna</h3>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Pengguna</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Kontak</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Status</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-9 h-9 rounded-full ring-2 ring-gray-100 dark:ring-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $user->email }}</p>
                            @if($user->phone)<p class="text-xs text-gray-400">{{ $user->phone }}</p>@endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <span class="flex items-center space-x-1.5 text-xs font-medium {{ $user->is_active ? 'text-green-600' : 'text-red-500' }}">
                                <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                <span>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="p-2 text-amber-600 bg-amber-50 dark:bg-amber-900/20 rounded-lg hover:bg-amber-100 transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <p class="text-sm text-gray-500">{{ $users->total() }} pengguna</p>
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
