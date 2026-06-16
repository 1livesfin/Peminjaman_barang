<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Laporan Pengembalian') }}
            </h2>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors print:hidden">
                <i data-lucide="printer" class="w-5 h-5"></i> Cetak Laporan
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-center mb-6 text-gray-900 dark:text-white">Laporan Pengembalian Barang</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">ID</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Peminjam</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Barang</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Tgl Dikembalikan</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm font-semibold">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                        <tr>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">#{{ $return->id }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $return->loan->user->name ?? '-' }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">
                                @if($return->loan)
                                    @foreach($return->loan->details as $detail)
                                        <div>{{ $detail->item->nama_barang ?? 'Unknown' }} ({{ $detail->jumlah }})</div>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $return->tanggal_dikembalikan }}</td>
                            <td class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-sm">{{ $return->kondisi_barang }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="border border-gray-200 dark:border-gray-600 px-4 py-4 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
