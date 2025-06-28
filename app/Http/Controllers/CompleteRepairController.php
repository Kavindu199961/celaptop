<?php

namespace App\Http\Controllers;
use App\Models\CompletedRepair;

use Illuminate\Http\Request;

class CompleteRepairController extends Controller
{
     public function index(Request $request)
    {
        $search = $request->input('search');
        
        $repairs = CompletedRepair::when($search, function($query) use ($search) {
                $query->where('customer_number', 'like', '%'.$search.'%')
                      ->orWhere('customer_name', 'like', '%'.$search.'%')
                      ->orWhere('serial_number', 'like', '%'.$search.'%')
                      ->orWhere('device', 'like', '%'.$search.'%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.complete-repair.index', compact('repairs'));
    }

    public function destroy($id)
    {
        $repair = CompletedRepair::findOrFail($id);
        $repair->delete();

        return redirect()->route('admin.complete-repair.index')->with('success', 'Repair record deleted successfully.');
    }
}
