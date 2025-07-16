<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CreditShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $userId = Auth::id();

        $shops = CreditShop::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.credit_shop.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.credit_shop.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        CreditShop::create($validated);

        return redirect()->route('user.credit_shop.index')
            ->with('success', 'Credit shop created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $creditShop = CreditShop::findOrFail($id);
        return view('user.credit_shop.show', compact('creditShop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $creditShop = CreditShop::findOrFail($id);
        return view('user.credit_shop.edit', compact('creditShop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $creditShop = CreditShop::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $creditShop->update($validated);

        return redirect()->route('user.credit_shop.index')
            ->with('success', 'Credit shop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $creditShop = CreditShop::findOrFail($id);
        $creditShop->delete();

        return redirect()->route('user.credit_shop.index')
            ->with('success', 'Credit shop deleted successfully.');
    }
}