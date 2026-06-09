<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $availableItems = Item::where('status', 'tersedia')->count();
        $borrowedItems = Item::where('status', 'dipinjam')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalBorrowings = Borrowing::count();
        $pendingBorrowings = Borrowing::where('status', 'menunggu')->count();
        $overdueBorrowings = Borrowing::where('status', 'dipinjam')
            ->where('return_date', '<', today())->count();

        // Monthly borrowings for chart (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => Borrowing::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Popular items (most borrowed)
        $popularItems = Item::withCount(['borrowingDetails as borrow_count'])
            ->orderBy('borrow_count', 'desc')
            ->limit(5)
            ->get();

        // Recent activity
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Recent borrowings
        $recentBorrowings = Borrowing::with(['user', 'details.item'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalItems', 'availableItems', 'borrowedItems',
            'totalUsers', 'totalBorrowings', 'pendingBorrowings',
            'overdueBorrowings', 'monthlyData', 'popularItems',
            'recentActivities', 'recentBorrowings'
        ));
    }
}
