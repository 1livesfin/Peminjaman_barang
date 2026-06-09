@extends('layouts.app')
@section('title', 'Pengembalian Barang')
@section('header', 'Pengembalian Barang')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Riwayat Pengembalian</h3>
        <form action="{{ route('returns.index') }}" method="GET" class="flex gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjaman..."
                       class="pl-8 pr-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl text-xs bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-xl text-xs hover:bg-indigo-700 transition-colors">Cari</button>
        </form>
    </div>

    @if($returns->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-undo-alt text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada pengembalian</h3>
            <p class="text-sm text-gray-400">Proses pengembalian dari halaman daftar peminjaman</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Pengembalian</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Tgl Kembali</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Denda</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($returns as $return)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-5 py-4">
                            <p class="font-mono text-xs font-semibold text-cyan-600 dark:text-cyan-400">{{ $return->return_number }}</p>
                            <p class="text-xs text-gray-400">Ref: {{ $return->borrowing->borrowing_number }}</p>
                        </td>
                        <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $return->borrowing->borrower_name }}</td>
                        <td class="px-5 py-4 hidden md:table-cell text-sm text-gray-600 dark:text-gray-400">{{ $return->return_date->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $return->overall_condition === 'baik' ? 'bg-green-100 text-green-700' : ($return->overall_condition === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ match($return->overall_condition) { 'baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat', default=>'–' } }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            @if($return->late_fine > 0)
                                <span class="text-sm font-semibold text-red-600">Rp {{ number_format($return->late_fine,0,',','.') }}</span>
                                @if($return->is_paid)<span class="ml-2 text-xs text-green-500"><i class="fas fa-check-circle"></i> Lunas</span>@endif
                            @else
                                <span class="text-sm text-gray-400">–</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('returns.show', $return) }}" class="p-2 text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 rounded-lg hover:bg-cyan-100 transition-colors inline-flex">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <p class="text-sm text-gray-500">{{ $returns->total() }} data</p>
            {{ $returns->links() }}
        </div>
    @endif
</div>
@endsection
