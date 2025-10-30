<?php

namespace App\Http\Controllers;

use App\Models\CompleteShopRepair;
use App\Models\RepairItem;
use App\Models\ShopNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

            return redirect()->route('shop_names.index')
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

            return redirect()->route('shop_names.index')
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
            return redirect()->route('shop_names.index')
                ->with('error', 'Failed to delete shop: ' . $e->getMessage());
        }
    }

    // Shop Show Method (1 parameter) - RENAMED to show()
    public function show($id)
    {
        $shop = ShopNames::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.shop_names.show', compact('shop'));
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

        return view('user.shop_names.repair_items.index', compact('shop', 'repairItems', 'completedRepairs'));
    }

    public function editRepairItem($shopId, $repairItemId)
    {
        $repairItem = RepairItem::where('id', $repairItemId)
            ->where('shop_id', $shopId)
            ->whereHas('shop', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $repairItemData = $repairItem->toArray();
        $repairItemData['images'] = $repairItem->images ? json_decode($repairItem->images, true) : [];

        return response()->json($repairItemData);
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
                'repair_items.*.images' => 'nullable|array',
                'repair_items.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $createdItems = [];

            foreach ($validated['repair_items'] as $index => $itemData) {
                $imagePaths = [];
                
                if ($request->hasFile("repair_items.{$index}.images")) {
                    foreach ($request->file("repair_items.{$index}.images") as $imageIndex => $image) {
                        try {
                            if ($image->isValid()) {
                                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                                $extension = $image->getClientOriginalExtension();
                                $filename = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;
                                
                                $path = $image->storeAs('repairs', $filename, 'public');
                                $imagePaths[] = $path;
                            }
                        } catch (\Exception $e) {
                            Log::error("Failed to upload image for item {$index}: " . $e->getMessage());
                            continue;
                        }
                    }
                }
                
                $itemData['shop_id'] = $shop->id;
                $itemData['images'] = !empty($imagePaths) ? json_encode($imagePaths) : null;
                
                $itemData['hdd'] = isset($itemData['hdd']) ? 1 : 0;
                $itemData['ssd'] = isset($itemData['ssd']) ? 1 : 0;
                $itemData['nvme'] = isset($itemData['nvme']) ? 1 : 0;
                $itemData['battery'] = isset($itemData['battery']) ? 1 : 0;
                $itemData['dvd_rom'] = isset($itemData['dvd_rom']) ? 1 : 0;
                $itemData['keyboard'] = isset($itemData['keyboard']) ? 1 : 0;

                $repairItem = RepairItem::create($itemData);
                $createdItems[] = $repairItem;
            }

            DB::commit();

            return redirect()->route('user.shop_names.repair_items.index', $shop->id)
                             ->with('success', count($createdItems) . ' repair items added successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to save repair items: ' . $e->getMessage(), [
                'shop_id' => $shopId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                             ->with('error', 'Failed to save repair items: ' . $e->getMessage())
                             ->withInput();
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
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $validated['hdd'] = $request->has('hdd') ? 1 : 0;
        $validated['ssd'] = $request->has('ssd') ? 1 : 0;
        $validated['nvme'] = $request->has('nvme') ? 1 : 0;
        $validated['battery'] = $request->has('battery') ? 1 : 0;
        $validated['dvd_rom'] = $request->has('dvd_rom') ? 1 : 0;
        $validated['keyboard'] = $request->has('keyboard') ? 1 : 0;

        try {
            $existingImages = $request->input('existing_images', []);
            $newImagePaths = [];
            
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $image->getClientOriginalExtension();
                        $filename = $originalName . '_' . time() . '_' . uniqid() . '.' . $extension;
                        
                        $path = $image->storeAs('repairs', $filename, 'public');
                        $newImagePaths[] = $path;
                    }
                }
            }
            
            $allImages = array_merge($existingImages, $newImagePaths);
            $validated['images'] = !empty($allImages) ? json_encode($allImages) : null;

            $repairItem->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Repair item updated successfully',
                'item' => $repairItem
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update repair item: ' . $e->getMessage(), [
                'repair_item_id' => $repairItemId,
                'shop_id' => $shopId,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update repair item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyRepairItem($shopId, $repairItemId)
    {
        try {
            $repairItem = RepairItem::where('id', $repairItemId)
                ->where('shop_id', $shopId)
                ->whereHas('shop', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();

            if ($repairItem->images) {
                $images = json_decode($repairItem->images, true);
                foreach ($images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $repairItem->delete();

            return redirect()->back()
                ->with('success', 'Repair item deleted successfully');

        } catch (\Exception $e) {
            Log::error('Failed to delete repair item: ' . $e->getMessage(), [
                'repair_item_id' => $repairItemId,
                'shop_id' => $shopId,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete repair item: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $shopId, $repairItemId)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,canceled',
            'final_price' => 'required_if:status,completed|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $repairItem = RepairItem::where('id', $repairItemId)
            ->where('shop_id', $shopId)
            ->whereHas('shop', function ($query) {
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

    // Repair Item Show Method (2 parameters)
 public function showRepairItem($shopId, $repairItemId)
{
    $repairItem = RepairItem::where('id', $repairItemId)
        ->where('shop_id', $shopId)
        ->whereHas('shop', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('shop')
        ->firstOrFail();

    return view('user.shop_names.repair_items.show', compact('repairItem'));
}
}