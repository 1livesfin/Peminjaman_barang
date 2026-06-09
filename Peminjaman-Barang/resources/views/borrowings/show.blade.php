@extends('layouts.app')
@section('title', 'Detail Peminjaman - ' . $borrowing->borrowing_number)
@section('header', 'Detail Peminjaman')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        @php $badge = $borrowing->status_badge; @endphp
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="font-mono text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $borrowing->borrowing_number }}</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    @if($borrowing->is_late)
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ $borrowing->late_days }} hari
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Diajukan pada {{ $borrowing->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->isAdmin() && $borrowing->status === 'menunggu')
                    <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                            <i class="fas fa-check text-xs"></i><span>Setujui</span>
                        </button>
                    </form>
                    <button x-data onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="inline-flex items-center space-x-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors shadow-sm">
                        <i class="fas fa-times text-xs"></i><span>Tolak</span>
                    </button>
                @endif
                @if(auth()->user()->isAdmin() && $borrowing->status === 'dipinjam')
                    <a href="{{ route('returns.create', $borrowing) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors shadow-sm">
                        <i class="fas fa-undo text-xs"></i><span>Proses Pengembalian</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Borrower Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-indigo-600 text-xs"></i>
                </div>
                Informasi Peminjam
            </h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Nama</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</dd>
                </div>
                @if($borrowing->borrower_phone)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Telepon</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_phone }}</dd>
                </div>
                @endif
                @if($borrowing->borrower_department)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Departemen</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_department }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Diajukan oleh</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->user->name }}</dd>
                </div>
            </dl>
        </div>

        <!-- Schedule Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <div class="w-6 h-6 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar text-cyan-600 text-xs"></i>
                </div>
                Jadwal Peminjaman
            </h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Tanggal Pinjam</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrow_date->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Tanggal Kembali</dt>
                    <dd class="text-sm font-medium {{ $borrowing->is_late ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                        {{ $borrowing->return_date->format('d M Y') }}
                        @if($borrowing->is_late)
                            <span class="text-xs ml-1">(Terlambat)</span>
                        @endif
                    </dd>
                </div>
                @if($borrowing->actual_return_date)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Dikembalikan</dt>
                    <dd class="text-sm font-medium text-green-600">{{ $borrowing->actual_return_date->format('d M Y') }}</dd>
                </div>
                @endif
                @if($borrowing->late_fine > 0)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Denda</dt>
                    <dd class="text-sm font-semibold text-red-600">Rp {{ number_format($borrowing->late_fine, 0, ',', '.') }}</dd>
                </div>
                @endif
                @if($borrowing->approver)
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Disetujui oleh</dt>
                    <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->approver->name }}</dd>
                </div>
                @endif
            </dl>

            @if($borrowing->purpose)
            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-400 mb-1">Keperluan</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->purpose }}</p>
            </div>
            @endif

            @if($borrowing->rejection_reason)
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 rounded-xl">
                <p class="text-xs text-red-500 font-semibold mb-1">Alasan Penolakan:</p>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $borrowing->rejection_reason }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Barang yang Dipinjam</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Barang</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kondisi (Sebelum)</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kondisi (Sesudah)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($borrowing->details as $detail)
                    <tr>
                        <td class="px-5 py-3">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $detail->item->name }}</p>
                                <p class="text-xs text-gray-400">{{ $detail->item->code }} · {{ $detail->item->category->name }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-sm">{{ $detail->quantity }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $detail->condition_before === 'baik' ? 'bg-green-100 text-green-700' : ($detail->condition_before === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ match($detail->condition_before) { 'baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat', default=>'–' } }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($detail->condition_after)
                                <span class="text-xs px-2 py-1 rounded-full {{ $detail->condition_after === 'baik' ? 'bg-green-100 text-green-700' : ($detail->condition_after === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ match($detail->condition_after) { 'baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat', default=>'–' } }}
                                </span>
                                @if($detail->damage_notes)<p class="text-xs text-red-500 mt-1">{{ $detail->damage_notes }}</p>@endif
                            @else
                                <span class="text-xs text-gray-400">Belum dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if(auth()->user()->isAdmin() && $borrowing->status === 'menunggu')
<div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tolak Peminjaman</h3>
        <form action="{{ route('borrowings.reject', $borrowing) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" rows="4" required placeholder="Masukkan alasan penolakan..."
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="flex-1 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">Tolak Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
