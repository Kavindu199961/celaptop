<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        
        $stocks = Stock::when($search, function($query) use ($search) {
                $query->where('item_name', 'like', '%'.$search.'%')
                      ->orWhere('description', 'like', '%'.$search.'%')
                      ->orWhere('vender', 'like', '%'.$search.'%');
            })
            ->orderBy('stock_date', 'desc')
            ->paginate(10);
            
        return view('admin.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'whole_sale_price' => 'required|numeric|min:0',
                'retail_price' => 'required|numeric|min:0',
                'vender' => 'required|string|max:255',
                'stock_date' => 'required|date',
                'quantity' => 'required|integer|min:0',
            ]);

            // Create the record
            $stock = Stock::create($validated);

            return redirect()->route('admin.stock.index')
                             ->with('success', 'Stock item created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                             ->withErrors($e->validator)
                             ->withInput()
                             ->with('error', 'Validation failed. Please check the form fields.');

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Database error occurred. Please try again.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stock = Stock::findOrFail($id);
        return view('admin.stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
 * Display the specified vendor's items.
 */
public function vendorShow($vendor)
{
    $items = Stock::where('vender', $vendor)
                ->orderBy('item_name')
                ->get();
    
    if (request()->ajax()) {
        return response()->json($items);
    }

    return view('admin.stock.vendor-show', compact('items', 'vendor'));
}

/**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    $stock = Stock::findOrFail($id);
    
    if (request()->ajax()) {
        return response()->json($stock);
    }
    
    return view('admin.stock.edit', compact('stock'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        
        try {
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost' => 'required|numeric|min:0',
                'whole_sale_price' => 'required|numeric|min:0',
                'retail_price' => 'required|numeric|min:0',
                'vender' => 'required|string|max:255',
                'stock_date' => 'required|date',
                'quantity' => 'required|integer|min:0',
            ]);

            $stock->update($validated);

            return redirect()->route('admin.stock.index')
                             ->with('success', 'Stock item updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'An error occurred while updating the stock item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $itemName = $stock->item_name;
            $stock->delete();

            return redirect()->route('admin.stock.index')
                             ->with('success', 'Stock item (' . $itemName . ') deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.stock.index')
                             ->with('error', 'Failed to delete stock item: ' . $e->getMessage());
        }
    }

    /**
     * Get stock statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_items' => Stock::count(),
                'total_quantity' => Stock::sum('quantity'),
                'total_investment' => Stock::sum(DB::raw('cost * quantity')),
                'total_wholesale_value' => Stock::sum(DB::raw('whole_sale_price * quantity')),
                'total_retail_value' => Stock::sum(DB::raw('retail_price * quantity')),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}