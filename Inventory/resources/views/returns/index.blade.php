<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Pengembalian') }}
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Pengembalian</h3>
                <a href="{{ route('returns.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i data-lucide="plus" class="w-5 h-5"></i> Proses Pengembalian
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">ID</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">ID Peminjaman</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Tanggal Kembali</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Kondisi Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Catatan</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($returns as $return)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 font-medium">#{{ $return->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <span class="text-emerald-600">Pinjaman #{{ $return->loan_id }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($return->tanggal_dikembalikan)->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $return->kondisi_barang }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $return->catatan ?? '-' }}</td>
                            @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4 text-sm text-right space-x-2">
                                <form action="{{ route('returns.destroy', $return) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus catatan pengembalian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 inline-flex">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? '6' : '5' }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($returns->hasPages())
            <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                {{ $returns->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
