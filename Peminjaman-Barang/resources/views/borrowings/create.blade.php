@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('header', 'Ajukan Peminjaman Baru')
@section('content')
<div class="max-w-4xl mx-auto" x-data="borrowingForm()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Form Peminjaman Barang</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">No: <span class="font-mono font-semibold text-indigo-600">{{ $borrowingNumber }}</span></p>
                </div>
            </div>
        </div>

        <form action="{{ route('borrowings.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            @if($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 rounded-xl">
                <p class="text-sm font-semibold text-red-700 mb-2"><i class="fas fa-exclamation-circle mr-2"></i>Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li class="text-sm text-red-600">{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <!-- Peminjam Info -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <i class="fas fa-user mr-2 text-indigo-500"></i>Informasi Peminjam
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" name="borrower_name" value="{{ old('borrower_name', auth()->user()->name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No. Telepon</label>
                        <input type="text" name="borrower_phone" value="{{ old('borrower_phone', auth()->user()->phone) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Departemen / Unit</label>
                        <input type="text" name="borrower_department" value="{{ old('borrower_department', auth()->user()->department) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <i class="fas fa-calendar mr-2 text-indigo-500"></i>Jadwal Peminjaman
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" name="borrow_date" value="{{ old('borrow_date', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="return_date" value="{{ old('return_date') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Keperluan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keperluan / Tujuan <span class="text-red-500">*</span></label>
                <textarea name="purpose" rows="3" placeholder="Jelaskan keperluan peminjaman barang..." required
                          class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('purpose') }}</textarea>
            </div>

            <!-- Pilih Barang -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                    <i class="fas fa-boxes-stacked mr-2 text-indigo-500"></i>Barang yang Dipinjam
                </h3>

                <!-- Item List -->
                <div class="space-y-3" id="items-container">
                    <template x-for="(item, index) in selectedItems" :key="index">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600">
                            <select :name="`items[${index}][item_id]`" x-model="item.item_id" required
                                    class="flex-1 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $availableItem)
                                    <option value="{{ $availableItem->id }}" data-stock="{{ $availableItem->stock_available }}">
                                        {{ $availableItem->name }} ({{ $availableItem->category->name }}) - Stok: {{ $availableItem->stock_available }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" placeholder="Qty" required
                                   class="w-20 px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <button type="button" @click="removeItem(index)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex-shrink-0">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addItem()" class="mt-3 w-full py-2.5 border-2 border-dashed border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 rounded-xl text-sm font-medium hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all">
                    <i class="fas fa-plus mr-2"></i>Tambah Barang
                </button>

                @if($items->isEmpty())
                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <p class="text-sm text-amber-700 dark:text-amber-400"><i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada barang yang tersedia untuk dipinjam saat ini.</p>
                </div>
                @endif
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" :disabled="selectedItems.length === 0"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-cyan-500 text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane mr-2"></i>Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function borrowingForm() {
    return {
        selectedItems: [{ item_id: '', quantity: 1 }],
        addItem() {
            this.selectedItems.push({ item_id: '', quantity: 1 });
        },
        removeItem(index) {
            if (this.selectedItems.length > 1) {
                this.selectedItems.splice(index, 1);
            }
        }
    };
}
</script>
@endpush
@endsection
