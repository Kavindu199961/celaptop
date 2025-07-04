<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceWithStock;
use App\Models\InvoiceWithStockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TotalAmountController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
        $searchDate = $request->input('search_date'); // Add this line to define $searchDate

        // Standard Invoices Query
        $standardQuery = Invoice::where('user_id', $userId)
            ->select(
                'id',
                'invoice_number',
                DB::raw('DATE(issue_date) as date'),
                'total_amount',
                'issue_date',
                'customer_name'
            );

        // Stock Invoices Query with proper relationships
        $stockQuery = InvoiceWithStock::with(['items.stock'])
            ->where('user_id', $userId)
            ->select(
                'id',
                'invoice_number',
                DB::raw('DATE(issue_date) as date'),
                'total_amount',
                'issue_date',
                'customer_name'
            );

        // Apply search filter
       if ($search) {
    $standardQuery->where('invoice_number', 'like', '%' . $search . '%');
    $stockQuery->where('invoice_number', 'like', '%' . $search . '%');
}

// One day search filter
if ($searchDate) {
    $standardQuery->whereDate('issue_date', '=', $searchDate);
    $stockQuery->whereDate('issue_date', '=', $searchDate);
} else {
    // Apply date range filters if provided
    if ($startDate) {
        $standardQuery->whereDate('issue_date', '>=', $startDate);
        $stockQuery->whereDate('issue_date', '>=', $startDate);
    }
    if ($endDate) {
        $standardQuery->whereDate('issue_date', '<=', $endDate);
        $stockQuery->whereDate('issue_date', '<=', $endDate);
    }
}

        // Get standard invoices
        $standardInvoices = $standardQuery
            ->orderByDesc('date')
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'type' => 'Standard',
                    'invoice_number' => $invoice->invoice_number,
                    'date' => $invoice->date,
                    'amount' => $invoice->total_amount,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                ];
            });

        // Get stock invoices with detailed cost calculations
        $stockInvoices = $stockQuery
            ->orderByDesc('date')
            ->get()
            ->map(function ($invoice) {
                $totalCost = 0;
                $itemDetails = [];

                // Calculate cost for each item
                foreach ($invoice->items as $item) {
                    $costPrice = $this->getCostPrice($item);
                    $itemCost = $item->quantity * $costPrice;
                    $totalCost += $itemCost;
                    
                    $itemDetails[] = [
                        'id' => $item->id,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'amount' => $item->amount,
                        'cost_price' => $costPrice,
                        'item_cost' => $itemCost,
                        'item_profit' => $item->amount - $itemCost,
                    ];
                }

                $profit = $invoice->total_amount - $totalCost;
                $margin = $invoice->total_amount > 0 ? ($profit / $invoice->total_amount) * 100 : 0;

                return [
                    'id' => $invoice->id,
                    'type' => 'Stock',
                    'invoice_number' => $invoice->invoice_number,
                    'date' => $invoice->date,
                    'amount' => $invoice->total_amount,
                    'cost' => $totalCost,
                    'profit' => $profit,
                    'margin' => $margin,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                    'items' => $itemDetails,
                ];
            });

        // Calculate totals
        $standardTotal = $standardInvoices->sum('amount');
        $stockTotal = $stockInvoices->sum('amount');
        $stockCostTotal = $stockInvoices->sum('cost');
        $stockProfitTotal = $stockInvoices->sum('profit');
        $overallMargin = $stockTotal > 0 ? ($stockProfitTotal / $stockTotal) * 100 : 0;

        // Debug information
        Log::info('TotalAmountController Debug', [
            'standardCount' => $standardInvoices->count(),
            'stockCount' => $stockInvoices->count(),
            'stockTotal' => $stockTotal,
            'stockCostTotal' => $stockCostTotal,
            'stockProfitTotal' => $stockProfitTotal,
        ]);

        return view('user.total_amount.index', [
            'standardInvoices' => $standardInvoices,
            'stockInvoices' => $stockInvoices,
            'standardTotal' => $standardTotal,
            'stockTotal' => $stockTotal,
            'stockCostTotal' => $stockCostTotal,
            'stockProfitTotal' => $stockProfitTotal,
            'overallMargin' => $overallMargin,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'search' => $search,
        ]);
    }

    /**
     * Get cost price for an invoice item
     * 
     * @param InvoiceWithStockItem $item
     * @return float
     */
    private function getCostPrice($item)
    {
        // Priority order for getting cost price:
        // 1. cost_price from invoice item (if exists and > 0)
        // 2. cost from related stock (if exists)
        // 3. Default to 0

        if (!empty($item->cost_price) && $item->cost_price > 0) {
            return $item->cost_price;
        }

        if ($item->stock && !empty($item->stock->cost)) {
            return $item->stock->cost;
        }

        // Log when cost price is not found
        Log::warning('Cost price not found for item', [
            'item_id' => $item->id,
            'stock_id' => $item->stock_id,
            'invoice_id' => $item->invoice_with_stock_id,
            'cost_price' => $item->cost_price,
            'stock_cost' => $item->stock ? $item->stock->cost : 'Stock not found',
        ]);

        return 0;
    }

    /**
     * Get invoice details for a specific date and type
     */
    public function getInvoiceDetails(Request $request)
    {
        $userId = Auth::id();
        $date = $request->input('date');
        $type = $request->input('type');

        if ($type === 'standard') {
            $invoices = Invoice::where('user_id', $userId)
                ->whereDate('issue_date', $date)
                ->select('id', 'invoice_number', 'total_amount', 'issue_date', 'customer_name')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'total_amount' => $invoice->total_amount,
                        'issue_date' => $invoice->issue_date,
                        'customer_name' => $invoice->customer_name ?? 'N/A',
                    ];
                });
        } else {
            $invoices = InvoiceWithStock::with(['items.stock'])
                ->where('user_id', $userId)
                ->whereDate('issue_date', $date)
                ->get()
                ->map(function ($invoice) {
                    $totalCost = 0;
                    $itemDetails = [];

                    // Calculate detailed costs for each item
                    foreach ($invoice->items as $item) {
                        $costPrice = $this->getCostPrice($item);
                        $itemCost = $item->quantity * $costPrice;
                        $totalCost += $itemCost;
                        
                        $itemDetails[] = [
                            'id' => $item->id,
                            'description' => $item->description,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'amount' => $item->amount,
                            'cost_price' => $costPrice,
                            'item_cost' => $itemCost,
                            'item_profit' => $item->amount - $itemCost,
                            'stock_name' => $item->stock ? $item->stock->item_name : 'N/A',
                        ];
                    }

                    $profit = $invoice->total_amount - $totalCost;
                    $margin = $invoice->total_amount > 0 ? ($profit / $invoice->total_amount) * 100 : 0;
                    
                    return [
                        'id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'total_amount' => $invoice->total_amount,
                        'cost' => $totalCost,
                        'profit' => $profit,
                        'margin' => $margin,
                        'issue_date' => $invoice->issue_date,
                        'customer_name' => $invoice->customer_name ?? 'N/A',
                        'items' => $itemDetails,
                    ];
                });
        }

        return response()->json([
            'success' => true,
            'invoices' => $invoices,
            'type' => $type,
            'date' => $date
        ]);
    }

    /**
     * Get detailed breakdown for a specific invoice
     */
    public function getInvoiceBreakdown(Request $request)
    {
        $userId = Auth::id();
        $invoiceId = $request->input('invoice_id');
        $type = $request->input('type');

        if ($type === 'standard') {
            $invoice = Invoice::where('user_id', $userId)
                ->where('id', $invoiceId)
                ->first();

            if (!$invoice) {
                return response()->json(['success' => false, 'message' => 'Invoice not found']);
            }

            return response()->json([
                'success' => true,
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                    'issue_date' => $invoice->issue_date,
                ],
                'type' => 'standard'
            ]);
        } else {
            $invoice = InvoiceWithStock::with(['items.stock'])
                ->where('user_id', $userId)
                ->where('id', $invoiceId)
                ->first();

            if (!$invoice) {
                return response()->json(['success' => false, 'message' => 'Invoice not found']);
            }

            $totalCost = 0;
            $itemDetails = [];

            foreach ($invoice->items as $item) {
                $costPrice = $this->getCostPrice($item);
                $itemCost = $item->quantity * $costPrice;
                $totalCost += $itemCost;
                
                $itemDetails[] = [
                    'id' => $item->id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->amount,
                    'cost_price' => $costPrice,
                    'item_cost' => $itemCost,
                    'item_profit' => $item->amount - $itemCost,
                    'item_margin' => $item->amount > 0 ? (($item->amount - $itemCost) / $item->amount) * 100 : 0,
                    'stock_name' => $item->stock ? $item->stock->item_name : 'N/A',
                    'warranty' => $item->warranty,
                ];
            }

            $profit = $invoice->total_amount - $totalCost;
            $margin = $invoice->total_amount > 0 ? ($profit / $invoice->total_amount) * 100 : 0;

            return response()->json([
                'success' => true,
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'cost' => $totalCost,
                    'profit' => $profit,
                    'margin' => $margin,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                    'issue_date' => $invoice->issue_date,
                    'items' => $itemDetails,
                ],
                'type' => 'stock'
            ]);
        }
    }

    /**
     * Update cost prices for existing invoice items (utility method)
     */
    public function updateCostPrices()
    {
        $userId = Auth::id();
        
        $updatedCount = 0;
        
        // Get all invoice items without cost_price or with 0 cost_price
        $items = InvoiceWithStockItem::with('stock')
            ->where('user_id', $userId)
            ->where(function($query) {
                $query->whereNull('cost_price')
                      ->orWhere('cost_price', 0);
            })
            ->get();

        foreach ($items as $item) {
            if ($item->stock && $item->stock->cost > 0) {
                $item->update(['cost_price' => $item->stock->cost]);
                $updatedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Updated {$updatedCount} items with cost prices",
            'updated_count' => $updatedCount
        ]);
    }
}