<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Shop::with('items')
                    ->where('user_id', $userId)
                    ->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shop_name', 'like', "%$search%")
                    ->orWhere('phone_number', 'like', "%$search%")
                    ->orWhereHas('items', function ($q) use ($search) {
                        $q->where('item_name', 'like', "%$search%")
                            ->orWhere('serial_number', 'like', "%$search%");
                    });
            });
        }

        $shops = $query->paginate(10);
        return view('user.shop.index', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        $shop = Shop::create([
            'user_id' => Auth::id(),
            'shop_name' => $request->shop_name,
            'phone_number' => $request->phone_number,
        ]);

        foreach ($request->items as $item) {
            $shop->items()->create([
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'warranty' => $item['warranty'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'price' => $item['price'] ?? null,
                'date' => $item['date'] ?? now(),
            ]);
        }

        return redirect()->route('user.shop.index')->with('success', 'Shop and items added successfully');
    }

    public function edit($id)
    {
        $shop = Shop::with('items')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json($shop);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.price' => 'nullable|numeric|min:0',
        ]);

        $shop = Shop::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $shop->update([
            'shop_name' => $request->shop_name,
            'phone_number' => $request->phone_number,
        ]);

        // Delete old items and create new ones
        $shop->items()->delete();

        foreach ($request->items as $item) {
            $shop->items()->create([
                'item_name' => $item['item_name'],
                'description' => $item['description'] ?? null,
                'warranty' => $item['warranty'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'price' => $item['price'] ?? null,
                'date' => $item['date'] ?? now(),
            ]);
        }

        return redirect()->route('user.shop.index')->with('success', 'Shop and items updated successfully');
    }

    public function destroy($id)
    {
        $shop = Shop::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $shop->delete();
        return redirect()->route('user.shop.index')->with('success', 'Shop and items deleted successfully');
    }

    public function show($id)
    {
        $shop = Shop::with('items')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.shop.show', compact('shop'));
    }
}
