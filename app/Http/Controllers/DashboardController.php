<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->roles === 'admin') {
            // Stats for Admin Dashboard
            $totalSales = Transaction::whereIn('status', ['SUCCESS', 'DELIVERED'])->count();
            $totalRevenue = Transaction::whereIn('status', ['SUCCESS', 'DELIVERED'])->sum('total_price');
            $totalCustomers = User::where('roles', 'user')->count();
            $totalProducts = Product::count();

            // Recent Transactions
            $recentTransactions = Transaction::orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Top Selling Products
            $topSelling = TransactionItem::select('products_id', DB::raw('count(*) as total_sold'))
                ->groupBy('products_id')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->with('product')
                ->get();

            return view('pages.dashboard.admin', compact(
                'totalSales',
                'totalRevenue',
                'totalCustomers',
                'totalProducts',
                'recentTransactions',
                'topSelling'
            ));
        } else {
            // Stats for User Dashboard
            $totalOrders = Transaction::where('users_id', $user->id)->count();
            $totalSpent = Transaction::where('users_id', $user->id)
                ->whereIn('status', ['SUCCESS', 'DELIVERED'])
                ->sum('total_price');
            $pendingOrders = Transaction::where('users_id', $user->id)
                ->where('status', 'PENDING')
                ->count();

            // User's Recent Transactions
            $recentTransactions = Transaction::where('users_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('pages.dashboard.user', compact(
                'totalOrders',
                'totalSpent',
                'pendingOrders',
                'recentTransactions'
            ));
        }
    }
}
