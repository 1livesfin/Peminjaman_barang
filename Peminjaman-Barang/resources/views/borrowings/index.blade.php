@extends('layouts.app')
@section('title', 'Daftar Peminjaman')
@section('header', 'Daftar Peminjaman')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-sm text-gray-500 dark:text-gray-400">
        @if(auth()->user()->isAdmin()) Kelola semua permintaan peminjaman barang @else Daftar peminjaman Anda @endif
    </p>
    <div class="flex gap-2">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('borrowings.export.pdf') }}" class="inline-flex items-center space-x-2 bg-red-50 text-red-600 border border-red-200 px-4 py-2.5 rounded-xl font-medium text-sm hover:bg-red-100 transition-colors">
            <i class="fas fa-file-pdf text-xs"></i><span>PDF</span>
        </a>
        @endif
        <a href="{{ route('borrowings.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white px-4 py-2.5 rounded-xl font-medium text-sm shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 hover:-translate-y-0.5 transition-all">
            <i class="fas fa-plus text-xs"></i><span>Ajukan Peminjaman</span>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 mb-6 shadow-sm">
    <form action="{{ route('borrowings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau nama peminjam..."
                   class="w-full pl-9 pr-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <select name="status" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">Filter</button>
        @if(request()->hasAny(['search','status','date_from','date_to']))
            <a href="{{ route('borrowings.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-sm">Reset</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    @if($borrowings->isEmpty())
        <div class="text-center py-24">
            <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada peminjaman</h3>
            <a href="{{ route('borrowings.create') }}" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-medium mt-4 hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus"></i><span>Ajukan Peminjaman</span>
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Peminjaman</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peminjam</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">Barang</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($borrowings as $borrowing)
                        @php $badge = $borrowing->status_badge; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $borrowing->borrowing_number }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $borrowing->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-indigo-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $borrowing->borrower_name }}</p>
                                        @if($borrowing->borrower_department)
                                            <p class="text-xs text-gray-400">{{ $borrowing->borrower_department }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden md:table-cell">
                                <div class="space-y-1">
                                    @foreach($borrowing->details->take(2) as $detail)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-lg">
                                            {{ $detail->item->name }} ({{ $detail->quantity }})
                                        </span>
                                    @endforeach
                                    @if($borrowing->details->count() > 2)
                                        <span class="text-xs text-gray-400">+{{ $borrowing->details->count() - 2 }} lainnya</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">s/d {{ $borrowing->return_date->format('d M Y') }}</p>
                                @if($borrowing->is_late)
                                    <span class="text-xs text-red-500 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ $borrowing->late_days }} hari</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('borrowings.show', $borrowing) }}" class="p-2 text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 transition-colors" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    @if(auth()->user()->isAdmin() && $borrowing->status === 'menunggu')
                                    <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-green-600 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 transition-colors" title="Setujui">
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if(auth()->user()->isAdmin() && $borrowing->status === 'dipinjam')
                                    <a href="{{ route('returns.create', $borrowing) }}" class="p-2 text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 rounded-lg hover:bg-cyan-100 transition-colors" title="Proses Pengembalian">
                                        <i class="fas fa-undo text-xs"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <p class="text-sm text-gray-500">Menampilkan {{ $borrowings->firstItem() }}–{{ $borrowings->lastItem() }} dari {{ $borrowings->total() }} data</p>
            {{ $borrowings->links() }}
        </div>
    @endif
</div>
@endsection
