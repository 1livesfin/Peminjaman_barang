@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('header', 'Riwayat Peminjaman')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
        <form action="{{ route('borrowings.history') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tanggal"
                   class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tanggal"
                   class="px-3 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors">
                <i class="fas fa-filter mr-1"></i>Filter
            </button>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('borrowings.export.pdf', request()->all()) }}" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors">
                <i class="fas fa-file-pdf mr-1"></i>Export PDF
            </a>
            @endif
        </form>
    </div>

    @if($borrowings->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-history text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada riwayat</h3>
            <p class="text-gray-400 text-sm">Riwayat peminjaman yang selesai akan muncul di sini</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Peminjaman</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Tanggal</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Denda</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($borrowings as $borrowing)
                        @php $badge = $borrowing->status_badge; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-5 py-4">
                                <p class="font-mono text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $borrowing->borrowing_number }}</p>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</td>
                            <td class="px-5 py-4 hidden md:table-cell text-gray-500 dark:text-gray-400 text-xs">
                                {{ $borrowing->borrow_date->format('d M Y') }} — {{ $borrowing->return_date->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            </td>
                            <td class="px-5 py-4 text-right text-sm font-medium {{ $borrowing->late_fine > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $borrowing->late_fine > 0 ? 'Rp '.number_format($borrowing->late_fine,0,',','.') : '–' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('borrowings.show', $borrowing) }}" class="p-2 text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg hover:bg-indigo-100 transition-colors inline-flex">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <p class="text-sm text-gray-500">{{ $borrowings->total() }} data ditemukan</p>
            {{ $borrowings->links() }}
        </div>
    @endif
</div>
@endsection
