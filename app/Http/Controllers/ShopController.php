<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopItem;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with('items')->latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shop_name', 'like', "%$search%")
                  ->orWhere('phone_number', 'like', "%$search%")
                  ->orWhereHas('items', function($q) use ($search) {
                      $q->where('item_name', 'like', "%$search%")
                        ->orWhere('serial_number', 'like', "%$search%");
                  });
            });
        }
        
        $shops = $query->paginate(10);
        return view('admin.shop.index', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $shop = Shop::create([
            'shop_name' => $request->shop_name,
            'phone_number' => $request->phone_number,
        ]);

        foreach ($request->items as $item) {
            $shop->items()->create([
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'warranty' => $item['warranty'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'price' => $item['price'],
                'date' => $item['date'] ?? now(),
            ]);
        }

        return redirect()->route('admin.shop.index')->with('success', 'Shop and items added successfully');
    }

    public function edit(Shop $shop)
    {
        return response()->json($shop->load('items'));
    }

    public function update(Request $request, Shop $shop)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $shop->update([
            'shop_name' => $request->shop_name,
            'phone_number' => $request->phone_number,
        ]);

        // Delete all existing items and create new ones
        $shop->items()->delete();
        
        foreach ($request->items as $item) {
            $shop->items()->create([
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'warranty' => $item['warranty'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'price' => $item['price'],
                'date' => $item['date'] ?? now(),
            ]);
        }

        return redirect()->route('admin.shop.index')->with('success', 'Shop and items updated successfully');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('admin.shop.index')->with('success', 'Shop and items deleted successfully');
    }

    public function show(Shop $shop)
{
    return view('admin.shop.show', compact('shop'));
}
}