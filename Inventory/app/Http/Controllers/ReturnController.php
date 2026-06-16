<?php

namespace App\Http\Controllers;

use App\Models\ReturnItem;
use App\Models\Loan;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnItem::with('loan.user', 'loan.details.item')->paginate(10);
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $loans = Loan::where('status', 'Dipinjam')->get();
        return view('returns.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'tanggal_dikembalikan' => 'required|date',
            'kondisi_barang' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        // Update loan status
        $loan->update(['status' => 'Dikembalikan']);

        // Return stock
        foreach ($loan->details as $detail) {
            $detail->item->increment('stok', $detail->jumlah);
        }

        ReturnItem::create($request->all());

        return redirect()->route('returns.index')->with('success', 'Pengembalian barang berhasil diproses.');
    }

    public function show(string $id)
    {
        $return = ReturnItem::with('loan.user', 'loan.details.item')->findOrFail($id);
        return view('returns.show', compact('return'));
    }

    public function edit(string $id)
    {
        $return = ReturnItem::findOrFail($id);
        $loans = Loan::all();
        return view('returns.edit', compact('return', 'loans'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal_dikembalikan' => 'required|date',
            'kondisi_barang' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $return = ReturnItem::findOrFail($id);
        $return->update($request->all());

        return redirect()->route('returns.index')->with('success', 'Data pengembalian diupdate.');
    }

    public function destroy(\App\Models\ReturnItem $return)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $return->delete();
        return redirect()->route('returns.index')->with('success', 'Catatan pengembalian dihapus.');
    }
}
