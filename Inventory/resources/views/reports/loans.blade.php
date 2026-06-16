<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Peminjaman') }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors print:hidden">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Laporan
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-center mb-6 text-gray-900 dark:text-white">Laporan Peminjaman Barang</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">ID</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Peminjam</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Barang</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Tgl Pinjam</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Tgl Kembali</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">#{{ $loan->id }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $loan->user->name ?? '-' }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">
                                @foreach($loan->details as $detail)
                                    <div>{{ $detail->item->nama_barang ?? 'Unknown' }} ({{ $detail->jumlah }})</div>
                                @endforeach
                            </td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $loan->tanggal_pinjam }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $loan->tanggal_kembali }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $loan->status }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="border border-gray-200 dark:border-gray-600 px-4 py-4 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
