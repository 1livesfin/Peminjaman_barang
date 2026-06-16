<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::sum('stok');
        $totalUsers = User::count();
        $borrowedItems = DB::table('loan_details')
            ->join('loans', 'loan_details.loan_id', '=', 'loans.id')
            ->whereIn('loans.status', ['Disetujui', 'Dipinjam'])
            ->sum('loan_details.jumlah');
            
        $availableItems = $totalItems - $borrowedItems;
        $totalLoans = Loan::count();
        
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $chartData = array_fill(0, 12, 0);
        
        // Get loans by month for current year (DB agnostic)
        $loans = Loan::whereYear('tanggal_pinjam', date('Y'))->get();
        foreach ($loans as $loan) {
            $month = (int) date('m', strtotime($loan->tanggal_pinjam));
            $chartData[$month - 1]++;
        }

        return view('dashboard', compact('totalItems', 'totalUsers', 'borrowedItems', 'availableItems', 'totalLoans', 'chartLabels', 'chartData'));
    }
}
