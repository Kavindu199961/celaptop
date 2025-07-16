<?php

namespace App\Http\Controllers;

use App\Models\CompleteShopRepair;
use App\Models\RepairItem;
use App\Models\ShopNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopNameController extends Controller
{
    // Shop Management Methods
    public function index()
    {
        $search = request('search');
        $userId = Auth::id();

        $shops = ShopNames::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.shop_names.index', compact('shops'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
            ]);

            $validated['user_id'] = Auth::id();

            ShopNames::create($validated);

            return redirect()->route('user.shop_names.index')
                ->with('success', 'Shop created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check the form fields.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function edit($id)
    {
        $shop = ShopNames::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($shop);
    }

    public function update(Request $request, $id)
    {
        $shop = ShopNames::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'contact' => 'nullable|string|max:255',
                'address' => 'nullable|string',
            ]);

            $shop->update($validated);

            return redirect()->route('user.shop_names.index')
                ->with('success', 'Shop updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the shop: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $shop = ShopNames::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $shopName = $shop->name;
            $shop->delete();

            return redirect()->route('user.shop_names.index')
                ->with('success', 'Shop (' . $shopName . ') deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.shop_names.index')
                ->with('error', 'Failed to delete shop: ' . $e->getMessage());
        }
    }

    // Repair Item Management Methods
    public function showRepairItems($shopId)
    {
        $shop = ShopNames::where('id', $shopId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $repairItems = RepairItem::where('shop_id', $shop->id)
            ->where('status', '!=', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $completedRepairs = CompleteShopRepair::with('repairItem')
            ->where('shop_id', $shop->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.shop_names.repair_items', compact('shop', 'repairItems', 'completedRepairs'));
    }

    public function editRepairItem($shopId, $repairItemId)
    {
        $repairItem = RepairItem::where('id', $repairItemId)
            ->where('shop_id', $shopId)
            ->whereHas('shop', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        return response()->json($repairItem);
    }

    public function storeRepairItems(Request $request, $shopId)
{
    DB::beginTransaction();

    try {
        $shop = ShopNames::where('id', $shopId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'repair_items' => 'required|array|min:1',
            'repair_items.*.item_name' => 'required|string|max:255',
            'repair_items.*.ram' => 'nullable|in:4GB,8GB,12GB,16GB,32GB,64GB',
            'repair_items.*.hdd' => 'nullable|boolean',
            'repair_items.*.ssd' => 'nullable|boolean',
            'repair_items.*.nvme' => 'nullable|boolean',
            'repair_items.*.battery' => 'nullable|boolean',
            'repair_items.*.dvd_rom' => 'nullable|boolean',
            'repair_items.*.keyboard' => 'nullable|boolean',
            'repair_items.*.price' => 'nullable|numeric|min:0',
            'repair_items.*.description' => 'nullable|string',
            'repair_items.*.serial_number' => 'nullable|string|max:255',
            'repair_items.*.date' => 'required|date',
            'repair_items.*.status' => 'required|in:pending,in_progress,completed,canceled',
        ]);

        foreach ($validated['repair_items'] as $itemData) {
            $itemData['shop_id'] = $shop->id;
            RepairItem::create($itemData);
        }

        DB::commit();

        // ✅ Redirect with success message
        return redirect()->route('user.shop_names.index')
                         ->with('success', 'Repair items added successfully.');

    } catch (\Exception $e) {
        DB::rollBack();

        // ✅ Redirect back with error
        return redirect()->back()
                         ->with('error', 'Failed to save repair items: ' . $e->getMessage());
    }
}


    public function updateRepairItem(Request $request, $shopId, $repairItemId)
    {
        $repairItem = RepairItem::where('id', $repairItemId)
            ->where('shop_id', $shopId)
            ->whereHas('shop', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'ram' => 'nullable|in:4GB,8GB,12GB,16GB,32GB,64GB',
            'hdd' => 'nullable|boolean',
            'ssd' => 'nullable|boolean',
            'nvme' => 'nullable|boolean',
            'battery' => 'nullable|boolean',
            'dvd_rom' => 'nullable|boolean',
            'keyboard' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,canceled',
        ]);

        // Handle checkbox values
        $validated['hdd'] = $request->has('hdd') ? 1 : 0;
        $validated['ssd'] = $request->has('ssd') ? 1 : 0;
        $validated['nvme'] = $request->has('nvme') ? 1 : 0;
        $validated['battery'] = $request->has('battery') ? 1 : 0;
        $validated['dvd_rom'] = $request->has('dvd_rom') ? 1 : 0;
        $validated['keyboard'] = $request->has('keyboard') ? 1 : 0;

        try {
            $repairItem->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Repair item updated successfully',
                'item' => $repairItem
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update repair item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyRepairItem($id)
    {
        $repairItem = RepairItem::where('id', $id)
            ->whereHas('shop', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $repairItem->delete();

        return redirect()->back()
            ->with('success', 'Repair item deleted successfully');
    }

    public function updateStatus(Request $request, $repairItemId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,canceled',
            'final_price' => 'required_if:status,completed|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $repairItem = RepairItem::where('id', $repairItemId)
            ->whereHas('shop', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $repairItem->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            CompleteShopRepair::create([
                'repair_item_id' => $repairItem->id,
                'shop_id' => $repairItem->shop_id,
                'user_id' => Auth::id(),
                'final_price' => $request->final_price,
                'notes' => $request->notes,
                'status' => 'completed'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}