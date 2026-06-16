<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between gap-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Peminjaman</h3>
                <a href="{{ route('loans.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i> Ajukan Peminjaman
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">ID</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Peminjam</th>
                            @endif
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Tanggal Pinjam</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Tenggat Waktu</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Status</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">#{{ $loan->id }}</td>
                            @if(auth()->user()->role === 'admin')
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $loan->user->name ?? '-' }}</td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                @foreach($loan->details as $detail)
                                    <div>{{ $detail->item->nama_barang ?? 'Unknown' }} ({{ $detail->jumlah }})</div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $loan->tanggal_pinjam }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $loan->tanggal_kembali }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusColors = [
                                        'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'Disetujui' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'Dipinjam' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'Dikembalikan' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400',
                                        'Ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded text-xs font-medium">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <form action="{{ route('loans.update', $loan) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1 pl-2 pr-6">
                                        <option value="Menunggu" {{ $loan->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="Disetujui" {{ $loan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="Dipinjam" {{ $loan->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="Dikembalikan" {{ $loan->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                        <option value="Ditolak" {{ $loan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '7' : '5' }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada riwayat peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($loans->hasPages())
            <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                {{ $loans->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
