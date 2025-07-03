<?php

namespace App\Http\Controllers;

use App\Models\CompletedRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompleteRepairController extends Controller
{
    /**
     * Display a listing of the user's completed repairs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

        $repairs = CompletedRepair::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('customer_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('serial_number', 'like', '%' . $search . '%')
                    ->orWhere('device', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('user.complete-repair.index', compact('repairs'));
    }

    /**
     * Remove the specified repair record (auth protected).
     */
    public function destroy($id)
    {
        $repair = CompletedRepair::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $repair->delete();

        return redirect()->route('user.complete-repair.index')
            ->with('success', 'Repair record deleted successfully.');
    }
}
