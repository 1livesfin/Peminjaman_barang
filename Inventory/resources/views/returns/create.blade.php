<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proses Pengembalian Barang') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded relative">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('returns.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="loan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Peminjaman</label>
                            <select name="loan_id" id="loan_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">-- Pilih Peminjaman Aktif --</option>
                                @foreach($loans as $loan)
                                    <option value="{{ $loan->id }}" {{ old('loan_id') == $loan->id ? 'selected' : '' }}>
                                        #{{ $loan->id }} - {{ $loan->details->first()->item->nama_barang ?? 'Barang' }} (Dipinjam oleh: {{ $loan->user->name ?? 'Anda' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_dikembalikan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pengembalian</label>
                                <input type="date" name="tanggal_dikembalikan" id="tanggal_dikembalikan" value="{{ old('tanggal_dikembalikan', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label for="kondisi_barang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kondisi Barang Saat Ini</label>
                                <select name="kondisi_barang" id="kondisi_barang" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="Baik" {{ old('kondisi_barang') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_barang') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ old('kondisi_barang') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                    <option value="Hilang" {{ old('kondisi_barang') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Pengembalian / Denda (Opsional)</label>
                            <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('returns.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-emerald-700">Proses Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
