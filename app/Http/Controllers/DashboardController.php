<?php

namespace App\Http\Controllers;

use App\Models\CompletedRepair;
use App\Models\LaptopRepair;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Total repairs count for the user
        $totalRepairs = LaptopRepair::where('user_id', $userId)->count();
        
        // Completed repairs count for the user
        $completedRepairs = CompletedRepair::where('user_id', $userId)->count();
        
        // Total stock items count for the user
        $totalStockItems = Stock::where('user_id', $userId)->count();
        
        // Total shops count for the user
        $totalShops = Shop::where('user_id', $userId)->count();

        // Weekly repairs count for the user
        $weeklyRepairs = LaptopRepair::where('user_id', $userId)
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count();
        
        // Monthly repairs count for the user
        $monthlyRepairs = LaptopRepair::where('user_id', $userId)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])->count();
        
        return view('user.dashboard', compact(
            'totalRepairs',
            'completedRepairs',
            'totalStockItems',
            'totalShops',
            'weeklyRepairs',
            'monthlyRepairs'
        ));
    }
}