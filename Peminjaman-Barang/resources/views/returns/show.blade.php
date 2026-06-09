@extends('layouts.app')
@section('title', 'Detail Pengembalian')
@section('header', 'Detail Pengembalian')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <p class="text-xs text-gray-400 mb-1">No. Pengembalian</p>
                <p class="text-xl font-bold font-mono text-cyan-600 dark:text-cyan-400">{{ $return->return_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 mb-1">Referensi Peminjaman</p>
                <p class="text-sm font-mono text-indigo-600 dark:text-indigo-400">{{ $return->borrowing->borrowing_number }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <p class="text-xs text-gray-400 mb-1">Peminjam</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $return->borrowing->borrower_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Tanggal Kembali</p>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $return->return_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Kondisi Umum</p>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $return->overall_condition === 'baik' ? 'bg-green-100 text-green-700' : ($return->overall_condition === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ match($return->overall_condition) { 'baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat', default=>'–' } }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Diproses oleh</p>
                <p class="text-sm text-gray-900 dark:text-white">{{ $return->processor?->name ?? '–' }}</p>
            </div>
            @if($return->late_days > 0)
            <div>
                <p class="text-xs text-gray-400 mb-1">Keterlambatan</p>
                <p class="text-sm font-semibold text-red-600">{{ $return->late_days }} hari</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Denda</p>
                <p class="text-sm font-bold text-red-600">Rp {{ number_format($return->late_fine,0,',','.') }}
                    <span class="ml-2 text-xs {{ $return->is_paid ? 'text-green-500' : 'text-red-400' }}">
                        {{ $return->is_paid ? '✓ Lunas' : '⚠ Belum Lunas' }}
                    </span>
                </p>
            </div>
            @endif
        </div>

        @if($return->notes)
        <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-400 mb-1">Catatan</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $return->notes }}</p>
        </div>
        @endif

        @if($return->proof_image)
        <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
            <p class="text-xs text-gray-400 mb-2">Bukti Pengembalian</p>
            <img src="{{ $return->proof_image_url }}" alt="Bukti" class="max-h-48 rounded-xl object-cover border border-gray-200">
        </div>
        @endif
    </div>

    <div class="flex gap-3">
        <a href="{{ route('returns.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
            ← Kembali ke Daftar
        </a>
        <a href="{{ route('borrowings.show', $return->borrowing) }}" class="px-5 py-2.5 text-sm font-medium text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl hover:bg-indigo-100 transition-colors">
            Lihat Peminjaman
        </a>
    </div>
</div>
@endsection
