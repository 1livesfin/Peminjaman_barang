@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('header', 'Proses Pengembalian Barang')
@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Borrowing Summary -->
    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-2xl p-5 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-1">Peminjaman yang Dikembalikan</p>
                <p class="text-lg font-bold text-indigo-700 dark:text-indigo-300 font-mono">{{ $borrowing->borrowing_number }}</p>
                <p class="text-sm text-indigo-600 dark:text-indigo-400">{{ $borrowing->borrower_name }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-indigo-500 mb-1">Batas Kembali</p>
                <p class="text-sm font-semibold {{ $lateDays > 0 ? 'text-red-600' : 'text-indigo-700 dark:text-indigo-300' }}">
                    {{ $borrowing->return_date->format('d M Y') }}
                </p>
                @if($lateDays > 0)
                    <p class="text-xs text-red-500 font-semibold mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ $lateDays }} hari
                    </p>
                @endif
            </div>
        </div>

        @if($lateDays > 0)
        <div class="mt-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl">
            <p class="text-sm font-semibold text-red-700 dark:text-red-400">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Denda keterlambatan: <span class="text-lg">Rp {{ number_format($lateFine, 0, ',', '.') }}</span>
            </p>
            <p class="text-xs text-red-500 mt-0.5">Dihitung dari {{ $lateDays }} hari × Rp 10.000/hari</p>
        </div>
        @endif
    </div>

    <!-- Items being returned -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Barang yang Dikembalikan</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($borrowing->details as $detail)
            <div class="p-4 flex items-center space-x-4">
                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-gray-400"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $detail->item->name }}</p>
                    <p class="text-xs text-gray-500">{{ $detail->item->code }} · Jumlah: {{ $detail->quantity }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $detail->condition_before === 'baik' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    Kondisi: {{ $detail->condition_before === 'baik' ? 'Baik' : 'Rusak Ringan' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Return Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center">
                    <i class="fas fa-undo text-cyan-600 dark:text-cyan-400 text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">Form Pengembalian</h2>
                    <p class="text-xs text-gray-500">No: <span class="font-mono font-semibold text-cyan-600">{{ $returnNumber }}</span></p>
                </div>
            </div>
        </div>

        <form action="{{ route('returns.store', $borrowing) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Pengembalian <span class="text-red-500">*</span></label>
                    <input type="date" name="return_date" value="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kondisi Umum Barang <span class="text-red-500">*</span></label>
                    <select name="overall_condition" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- Per-item condition -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Kondisi per Barang</label>
                <div class="space-y-3">
                    @foreach($borrowing->details as $idx => $detail)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                        <input type="hidden" name="items[{{ $idx }}][detail_id]" value="{{ $detail->id }}">
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ $detail->item->name }} (×{{ $detail->quantity }})</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Kondisi saat kembali</label>
                                <select name="items[{{ $idx }}][condition_after]" class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                                    <option value="baik">Baik</option>
                                    <option value="rusak_ringan">Rusak Ringan</option>
                                    <option value="rusak_berat">Rusak Berat</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Catatan kerusakan</label>
                                <input type="text" name="items[{{ $idx }}][damage_notes]" placeholder="Jika ada..."
                                       class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="3" placeholder="Catatan tambahan tentang pengembalian..."
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bukti Pengembalian (Foto)</label>
                <div x-data="{ preview: null }" class="border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl p-5 text-center hover:border-cyan-400 transition-colors cursor-pointer" onclick="document.getElementById('proofImg').click()">
                    <input type="file" id="proofImg" name="proof_image" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($event.target.files[0])">
                    <template x-if="!preview">
                        <div>
                            <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                            <p class="text-sm text-gray-400">Upload foto bukti pengembalian</p>
                        </div>
                    </template>
                    <template x-if="preview">
                        <img :src="preview" class="max-h-40 mx-auto rounded-lg object-cover">
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('borrowings.show', $borrowing) }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
