<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Item;
use App\Models\ItemReturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemReturn::with(['borrowing.user', 'borrowing.details.item', 'processor']);

        if ($request->filled('search')) {
            $query->whereHas('borrowing', function ($q) use ($request) {
                $q->where('borrowing_number', 'like', '%' . $request->search . '%')
                  ->orWhere('borrower_name', 'like', '%' . $request->search . '%');
            });
        }

        $returns = $query->latest()->paginate(10)->withQueryString();

        return view('returns.index', compact('returns'));
    }

    public function create(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'dipinjam') {
            return redirect()->route('borrowings.index')
                ->with('error', 'Peminjaman ini tidak bisa dikembalikan!');
        }

        $borrowing->load(['details.item', 'user']);

        $returnNumber = ItemReturn::generateNumber();
        $today = today();
        $lateDays = max(0, $today->diffInDays($borrowing->return_date, false) * -1);
        $lateFine = $lateDays * 10000;

        return view('returns.create', compact('borrowing', 'returnNumber', 'lateDays', 'lateFine'));
    }

    public function store(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'return_date'      => 'required|date',
            'overall_condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes'            => 'nullable|string',
            'proof_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'items'            => 'sometimes|array',
            'items.*.detail_id' => 'exists:borrowing_details,id',
            'items.*.condition_after' => 'in:baik,rusak_ringan,rusak_berat',
            'items.*.damage_notes'    => 'nullable|string',
        ]);

        $returnDate = Carbon::parse($validated['return_date']);
        $lateDays = max(0, $returnDate->diffInDays($borrowing->return_date, false) * -1);
        $lateFine = $lateDays * 10000;

        $proofImage = null;
        if ($request->hasFile('proof_image')) {
            $proofImage = $request->file('proof_image')->store('returns', 'public');
        }

        $return = ItemReturn::create([
            'return_number'     => ItemReturn::generateNumber(),
            'borrowing_id'      => $borrowing->id,
            'user_id'           => auth()->id(),
            'return_date'       => $returnDate,
            'overall_condition' => $validated['overall_condition'],
            'notes'             => $validated['notes'] ?? null,
            'proof_image'       => $proofImage,
            'late_fine'         => $lateFine,
            'late_days'         => $lateDays,
            'processed_by'      => auth()->id(),
            'processed_at'      => now(),
        ]);

        // Update borrowing details condition
        if ($request->filled('items')) {
            foreach ($request->items as $itemData) {
                BorrowingDetail::where('id', $itemData['detail_id'])->update([
                    'condition_after' => $itemData['condition_after'] ?? 'baik',
                    'damage_notes'    => $itemData['damage_notes'] ?? null,
                ]);
            }
        }

        // Restore stock
        foreach ($borrowing->details as $detail) {
            $item = $detail->item;
            $item->increment('stock_available', $detail->quantity);
            if ($item->stock_available > 0) {
                $item->update(['status' => 'tersedia']);
            }
        }

        // Update borrowing status
        $status = $lateDays > 0 ? 'terlambat' : 'dikembalikan';
        $borrowing->update([
            'status'            => 'dikembalikan',
            'actual_return_date' => $returnDate,
            'late_fine'         => $lateFine,
        ]);

        ActivityLog::log('return', "Pengembalian barang: {$return->return_number}", $borrowing);

        return redirect()->route('returns.show', $return)
            ->with('success', 'Pengembalian barang berhasil diproses!');
    }

    public function show(ItemReturn $return)
    {
        $return->load(['borrowing.user', 'borrowing.details.item', 'processor']);
        return view('returns.show', compact('return'));
    }
}
