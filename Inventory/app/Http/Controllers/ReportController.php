<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function loans(Request $request)
    {
        $loans = \App\Models\Loan::with('user', 'details.item')->latest()->get();
        return view('reports.loans', compact('loans'));
    }

    public function returns(Request $request)
    {
        $returns = \App\Models\ReturnItem::with('loan.user', 'loan.details.item')->latest()->get();
        return view('reports.returns', compact('returns'));
    }

    public function stock(Request $request)
    {
        $items = \App\Models\Item::with('category')->orderBy('nama_barang')->get();
        return view('reports.stock', compact('items'));
    }
}
