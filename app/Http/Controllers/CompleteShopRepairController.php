<?php

namespace App\Http\Controllers;

use App\Models\CompleteShopRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompleteShopRepairController extends Controller
{
    public function index(Request $request)
    {
        $query = CompleteShopRepair::with(['shop', 'repairItem']);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('repairItem', function($q) use ($search) {
                    $q->where('item_number', 'like', '%' . $search . '%');
                })->orWhereHas('shop', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        
        $repairs = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('user.shop_completed_repair.index', compact('repairs'));
    }

    public function edit($repair)
    {
        try {
            $repairRecord = CompleteShopRepair::with(['shop', 'repairItem'])->findOrFail($repair);
            
            return response()->json([
                'id' => $repairRecord->id,
                'shop_name' => $repairRecord->shop->name ?? 'N/A',
                'item_number' => $repairRecord->repairItem->item_number ?? 'N/A',
                'final_price' => $repairRecord->final_price,
                'status' => $repairRecord->status,
                'created_at' => $repairRecord->created_at->format('Y-m-d H:i')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Repair record not found'
            ], 404);
        }
    }

    public function update(Request $request, $repair)
    {
        $request->validate([
            'final_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:completed,cancelled,pending'
        ]);

        try {
            $repairRecord = CompleteShopRepair::findOrFail($repair);
            
            $repairRecord->update([
                'final_price' => $request->final_price,
                'status' => $request->status
            ]);

            return redirect()->route('user.shop_completed_repair.index')
                           ->with('success', 'Repair record updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.shop_completed_repair.index')
                           ->with('error', 'Error updating repair record: ' . $e->getMessage());
        }
    }

    public function destroy($repair)
    {
        try {
            $repairRecord = CompleteShopRepair::findOrFail($repair);
            $repairRecord->delete();
            
            return redirect()->route('user.shop_completed_repair.index')
                           ->with('success', 'Repair record deleted successfully!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.shop_completed_repair.index')
                           ->with('error', 'Repair record not found.');
        } catch (\Exception $e) {
            return redirect()->route('user.shop_completed_repair.index')
                           ->with('error', 'Error deleting repair record: ' . $e->getMessage());
        }
    }
}