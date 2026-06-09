<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'details.item']);

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('borrowing_number', 'like', '%' . $request->search . '%')
                  ->orWhere('borrower_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $items = Item::where('status', 'tersedia')
            ->where('stock_available', '>', 0)
            ->with('category')
            ->get();

        $borrowingNumber = Borrowing::generateNumber();

        return view('borrowings.create', compact('items', 'borrowingNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_name'       => 'required|string|max:200',
            'borrower_phone'      => 'nullable|string|max:20',
            'borrower_department' => 'nullable|string|max:100',
            'borrow_date'         => 'required|date|after_or_equal:today',
            'return_date'         => 'required|date|after:borrow_date',
            'purpose'             => 'required|string',
            'items'               => 'required|array|min:1',
            'items.*.item_id'     => 'required|exists:items,id',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

        // Validate stock availability
        foreach ($request->items as $detail) {
            $item = Item::find($detail['item_id']);
            if (!$item || $item->stock_available < $detail['quantity']) {
                return back()->with('error', "Stok barang '{$item->name}' tidak mencukupi!")->withInput();
            }
        }

        $borrowing = Borrowing::create([
            'borrowing_number'    => Borrowing::generateNumber(),
            'user_id'             => auth()->id(),
            'borrower_name'       => $validated['borrower_name'],
            'borrower_phone'      => $validated['borrower_phone'] ?? null,
            'borrower_department' => $validated['borrower_department'] ?? null,
            'borrow_date'         => $validated['borrow_date'],
            'return_date'         => $validated['return_date'],
            'purpose'             => $validated['purpose'],
            'status'              => 'menunggu',
        ]);

        foreach ($request->items as $detail) {
            $item = Item::find($detail['item_id']);
            BorrowingDetail::create([
                'borrowing_id'    => $borrowing->id,
                'item_id'         => $item->id,
                'quantity'        => $detail['quantity'],
                'condition_before' => $item->condition,
            ]);
        }

        ActivityLog::log('create', "Mengajukan peminjaman: {$borrowing->borrowing_number}", $borrowing);

        return redirect()->route('borrowings.show', $borrowing)
            ->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function show(Borrowing $borrowing)
    {
        $this->authorizeAccess($borrowing);
        $borrowing->load(['user', 'details.item.category', 'approver', 'return']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini tidak bisa disetujui!');
        }

        // Reduce stock
        foreach ($borrowing->details as $detail) {
            $item = $detail->item;
            $item->decrement('stock_available', $detail->quantity);
            if ($item->stock_available <= 0) {
                $item->update(['status' => 'dipinjam']);
            }
        }

        $borrowing->update([
            'status'      => 'dipinjam',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        ActivityLog::log('approve', "Menyetujui peminjaman: {$borrowing->borrowing_number}", $borrowing);

        return back()->with('success', 'Peminjaman berhasil disetujui!');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman ini tidak bisa ditolak!');
        }

        $borrowing->update([
            'status'           => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
        ]);

        ActivityLog::log('reject', "Menolak peminjaman: {$borrowing->borrowing_number}", $borrowing);

        return back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    public function history(Request $request)
    {
        $query = Borrowing::with(['user', 'details.item'])
            ->whereIn('status', ['dikembalikan', 'ditolak', 'terlambat']);

        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.history', compact('borrowings'));
    }

    public function exportPdf(Request $request)
    {
        $query = Borrowing::with(['user', 'details.item']);

        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $borrowings = $query->latest()->get();
        $pdf = Pdf::loadView('exports.borrowings-pdf', compact('borrowings'));

        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    private function authorizeAccess(Borrowing $borrowing): void
    {
        if (auth()->user()->role !== 'admin' && $borrowing->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
