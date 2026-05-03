<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Expense;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers      = User::where('role', 'user')->count();
        $totalExpenses   = Expense::sum('amount');
        $totalCategories = Category::count();
        $recentUsers     = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalExpenses',
            'totalCategories',
            'recentUsers'
        ));
    }
}
