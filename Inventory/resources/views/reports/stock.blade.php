<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Stok Barang') }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors print:hidden">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Laporan
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-center mb-6 text-gray-900 dark:text-white">Laporan Ketersediaan Stok Barang</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Kode</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Nama Barang</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Kategori</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Stok Tersedia</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Kondisi</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $item->kode_barang }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $item->nama_barang }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $item->category->name ?? '-' }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-bold {{ $item->stok < 5 ? 'text-red-500' : 'text-emerald-500' }}">{{ $item->stok }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $item->kondisi }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $item->lokasi }}</td>
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
