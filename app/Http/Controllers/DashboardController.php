<?php

namespace App\Http\Controllers;

use App\Models\CompletedRepair;
use App\Models\LaptopRepair;
use App\Models\Shop;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total repairs count
        $totalRepairs = LaptopRepair::count();
        
        // Completed repairs count
        $completedRepairs = CompletedRepair::count();
        
        // Total stock items count
        $totalStockItems = Stock::count();
        
        // Total shops count
        $totalShops = Shop::count();

        // Weekly repairs count
        $weeklyRepairs = LaptopRepair::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        
        // Monthly repairs count
        $monthlyRepairs = LaptopRepair::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();
        
        return view('admin.dashboard', compact(
            'totalRepairs',
            'completedRepairs',
            'totalStockItems',
            'totalShops',
            'weeklyRepairs',
            'monthlyRepairs'
        ));
    }
}