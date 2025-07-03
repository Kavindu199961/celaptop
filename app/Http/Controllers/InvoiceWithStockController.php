<?php

namespace App\Http\Controllers;

use App\Models\InvoiceWithStock;
use App\Models\InvoiceWithStockItem;
use App\Models\Stock;
use App\Models\MyShopDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceWithStockController extends Controller
{
    public function index()
    {
        $search = request('search');
        $userId = Auth::id();

        $invoices = InvoiceWithStock::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('sales_rep', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stocks = Stock::where('user_id', $userId)
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();

        return view('user.invoices_with_stock.index', compact('invoices', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'sales_rep' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'stock_id.*' => 'required|exists:stock,id',
            'qty.*' => 'required|numeric|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        for ($i = 0; $i < count($request->stock_id); $i++) {
            $totalAmount += $request->qty[$i] * $request->unit_price[$i];
        }

        // Create invoice
        $invoice = InvoiceWithStock::create([
            'invoice_number' => 'INV-' . str_pad(InvoiceWithStock::max('id') + 1, 6, '0', STR_PAD_LEFT),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'sales_rep' => $request->sales_rep,
            'issue_date' => $request->issue_date,
            'total_amount' => $totalAmount,
            'user_id' => Auth::id(),
        ]);

        // Create invoice items and update stock
        for ($i = 0; $i < count($request->stock_id); $i++) {
            $stock = Stock::findOrFail($request->stock_id[$i]);
            
            if ($stock->quantity < $request->qty[$i]) {
                $invoice->delete();
                return back()->with('error', "Not enough stock for {$stock->item_name}. Available: {$stock->quantity}");
            }

            InvoiceWithStockItem::create([
                'invoice_with_stock_id' => $invoice->id,
                'stock_id' => $request->stock_id[$i],
                'warranty' => $request->warranty[$i] ?? null,
                'quantity' => $request->qty[$i],
                'unit_price' => $request->unit_price[$i],
                'amount' => $request->qty[$i] * $request->unit_price[$i],
                'user_id' => Auth::id(),
            ]);

            $stock->decrement('quantity', $request->qty[$i]);
        }

        return redirect()->route('user.invoices_with_stock.print', $invoice->id)
            ->with('success', 'Invoice created successfully and stock updated');
    }

    public function show(InvoiceWithStock $invoiceWithStock)
    {
        $this->authorizeAccess($invoiceWithStock);
        return view('user.invoices_with_stock.show', compact('invoiceWithStock'));
    }

    public function download(InvoiceWithStock $invoiceWithStock)
    {
        $this->authorizeAccess($invoiceWithStock);

        $user = auth()->user();
        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoPath = null;

        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (!file_exists($logoPath) || !is_readable($logoPath)) {
                $logoPath = null;
            }
        }

        $viewName = $invoiceWithStock->items->count() > 10 
            ? 'user.invoices_with_stock.fullpdf' 
            : 'user.invoices_with_stock.pdf';

        $pdf = PDF::loadView($viewName, [
            'invoice' => $invoiceWithStock,
            'logoPath' => $logoPath,
            'shopDetail' => $shopDetail,
        ]);

        return $pdf->download('invoice-with-stock-' . $invoiceWithStock->invoice_number . '.pdf');
    }

    public function print(InvoiceWithStock $invoiceWithStock)
    {
        $this->authorizeAccess($invoiceWithStock);

        $user = auth()->user();
        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoBase64 = null;

        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoData);
            }
        }

        $viewName = $invoiceWithStock->items->count() > 10 
            ? 'user.invoices_with_stock.fullprint' 
            : 'user.invoices_with_stock.print';

        return view($viewName, compact('invoiceWithStock', 'logoBase64', 'shopDetail'));
    }

    public function destroy(InvoiceWithStock $invoiceWithStock)
    {
        $this->authorizeAccess($invoiceWithStock);
        
        foreach ($invoiceWithStock->items as $item) {
            if ($item->stock) {
                $item->stock->increment('quantity', $item->quantity);
            }
        }

        $invoiceWithStock->delete();
        
        return redirect()->route('user.invoices_with_stock.index')
            ->with('success', 'Invoice deleted successfully and stock quantities restored');
    }

    private function authorizeAccess(InvoiceWithStock $invoiceWithStock)
    {
        if ($invoiceWithStock->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
    }

    public function searchStock(Request $request)
    {
        $search = $request->get('term');
        
        if (empty($search) || strlen($search) < 2) {
            return response()->json([]);
        }
        
        $stocks = Stock::where('user_id', Auth::id())
            ->where('quantity', '>', 0)
            ->where('item_name', 'like', '%' . $search . '%')
            ->orderBy('item_name')
            ->limit(10)
            ->get();

        $results = [];
        foreach ($stocks as $stock) {
            $results[] = [
                'id' => $stock->id,
                'text' => $stock->item_name,
                'name' => $stock->item_name,
                'retail_price' => $stock->retail_price,
                'quantity' => $stock->quantity
            ];
        }

        return response()->json($results);
    }

    public function getProduct($id)
    {
        $product = Stock::where('user_id', Auth::id())
                      ->where('id', $id)
                      ->firstOrFail();
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->item_name,
            'price' => $product->retail_price,
            'quantity' => $product->quantity
        ]);
    }
}