<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $items = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $code = Item::generateCode();
        return view('items.create', compact('categories', 'code'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:items,code',
            'name'           => 'required|string|max:200',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'stock'          => 'required|integer|min:1',
            'condition'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'         => 'required|in:tersedia,dipinjam,tidak_tersedia,perbaikan',
            'location'       => 'nullable|string|max:100',
            'brand'          => 'nullable|string|max:100',
            'serial_number'  => 'nullable|string|max:100',
            'purchase_date'  => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'notes'          => 'nullable|string',
        ]);

        $validated['stock_available'] = $validated['stock'];

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $img) {
                $imagePaths[] = $img->store('items', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        $item = Item::create($validated);

        ActivityLog::log('create', "Menambah barang: {$item->name} ({$item->code})", $item);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(Item $item)
    {
        $item->load(['category', 'borrowingDetails.borrowing.user']);
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:items,code,' . $item->id,
            'name'           => 'required|string|max:200',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'stock'          => 'required|integer|min:1',
            'stock_available' => 'required|integer|min:0',
            'condition'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'status'         => 'required|in:tersedia,dipinjam,tidak_tersedia,perbaikan',
            'location'       => 'nullable|string|max:100',
            'brand'          => 'nullable|string|max:100',
            'serial_number'  => 'nullable|string|max:100',
            'purchase_date'  => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'notes'          => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $oldValues = $item->toArray();
        $item->update($validated);

        ActivityLog::log('update', "Memperbarui barang: {$item->name}", $item, $oldValues, $item->toArray());

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        if ($item->borrowingDetails()->whereHas('borrowing', function ($q) {
            $q->whereIn('status', ['dipinjam', 'disetujui', 'menunggu']);
        })->exists()) {
            return redirect()->route('items.index')
                ->with('error', 'Barang tidak dapat dihapus karena sedang dipinjam!');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        ActivityLog::log('delete', "Menghapus barang: {$item->name}", $item);
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
