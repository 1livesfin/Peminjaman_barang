<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $query = \App\Models\Loan::with('user', 'details.item')->latest();
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        $loans = $query->paginate(10);
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $items = \App\Models\Item::where('stok', '>', 0)->get();
        return view('loans.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        $item = \App\Models\Item::findOrFail($request->item_id);
        if ($item->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $loan = \App\Models\Loan::create([
            'user_id' => auth()->id(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'Menunggu',
            'catatan' => $request->catatan,
        ]);

        $loan->details()->create([
            'item_id' => $item->id,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman berhasil dibuat.');
    }

    public function edit(\App\Models\Loan $loan)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('loans.edit', compact('loan'));
    }

    public function update(Request $request, \App\Models\Loan $loan)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Dipinjam,Dikembalikan,Ditolak',
        ]);

        if ($request->status === 'Dipinjam' && $loan->status !== 'Dipinjam') {
            foreach ($loan->details as $detail) {
                $detail->item->decrement('stok', $detail->jumlah);
            }
        }

        $loan->update(['status' => $request->status]);

        return redirect()->route('loans.index')->with('success', 'Status peminjaman diupdate.');
    }

    public function destroy(\App\Models\Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Data peminjaman dihapus.');
    }
}
