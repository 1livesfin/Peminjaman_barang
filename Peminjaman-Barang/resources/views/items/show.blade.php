@extends('layouts.app')

@section('title', $item->name)
@section('header', 'Detail Barang')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image & Status Panel -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 relative">
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-box text-gray-300 dark:text-gray-500 text-6xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        @php
                            $statusMap = ['tersedia'=>'bg-green-500','dipinjam'=>'bg-amber-500','tidak_tersedia'=>'bg-red-500','perbaikan'=>'bg-blue-500'];
                            $statusLabel = ['tersedia'=>'Tersedia','dipinjam'=>'Dipinjam','tidak_tersedia'=>'Tidak Tersedia','perbaikan'=>'Perbaikan'];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white {{ $statusMap[$item->status] ?? 'bg-gray-500' }}">
                            <span class="w-2 h-2 bg-white rounded-full mr-1.5 animate-pulse"></span>
                            {{ $statusLabel[$item->status] ?? $item->status }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <div class="text-center mb-4">
                        <p class="text-xs text-gray-400 font-mono mb-1">{{ $item->code }}</p>
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">{{ $item->name }}</h1>
                        @if($item->brand)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->brand }}</p>
                        @endif
                    </div>

                    <!-- Stock bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                            <span>Stok Tersedia</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item->stock_available }}/{{ $item->stock }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                            @php $pct = $item->stock > 0 ? ($item->stock_available / $item->stock) * 100 : 0; @endphp
                            <div class="h-2 rounded-full {{ $pct > 50 ? 'bg-green-500' : ($pct > 20 ? 'bg-amber-500' : 'bg-red-500') }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin())
                    <div class="flex gap-2">
                        <a href="{{ route('items.edit', $item) }}" class="flex-1 py-2.5 text-center text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-cyan-500 rounded-xl hover:shadow-md transition-all">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="py-2.5 px-4 text-sm text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl hover:bg-red-100 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Detail Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Informasi Barang</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kategori</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kondisi</p>
                        <span class="text-xs px-2 py-1 rounded-full font-medium {{ $item->condition === 'baik' ? 'bg-green-100 text-green-700' : ($item->condition === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $item->condition_label }}
                        </span>
                    </div>
                    @if($item->location)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Lokasi</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->location }}</p>
                    </div>
                    @endif
                    @if($item->serial_number)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Serial Number</p>
                        <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $item->serial_number }}</p>
                    </div>
                    @endif
                    @if($item->purchase_date)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Beli</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $item->purchase_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    @if($item->purchase_price)
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Harga Beli</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
                @if($item->description)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-400 mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $item->description }}</p>
                </div>
                @endif
            </div>

            <!-- Borrow History -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Riwayat Peminjaman</h3>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($item->borrowingDetails->take(8) as $detail)
                        @php $badge = $detail->borrowing->status_badge; @endphp
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-indigo-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $detail->borrowing->borrower_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $detail->borrowing->borrow_date->format('d M Y') }} — {{ $detail->borrowing->return_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fas fa-clipboard-list text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-400">Belum ada riwayat peminjaman</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($item->stock_available > 0 && $item->status === 'tersedia')
            <a href="{{ route('borrowings.create') }}" class="block w-full py-3 text-center bg-gradient-to-r from-indigo-600 to-cyan-500 text-white font-semibold rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                <i class="fas fa-clipboard-list mr-2"></i> Pinjam Barang Ini
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
