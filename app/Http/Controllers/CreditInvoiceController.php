<?php

namespace App\Http\Controllers;

use App\Models\CreditInvoice;
use App\Models\CreditInvoiceItem;
use App\Models\CreditShop;
use App\Models\Cashier;
use App\Models\MyShopDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CreditInvoiceController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    
    // Monthly Summary Query
    $monthlyQuery = CreditInvoice::where('user_id', $userId)
        ->selectRaw('credit_shop_id, 
                    MONTH(created_at) as month, 
                    YEAR(created_at) as year, 
                    SUM(total_amount) as total,
                    SUM(total_amount - remaining_amount) as paid')
        ->groupBy('credit_shop_id', 'month', 'year')
        ->with('creditShop');
    
    if(request('month')) {
        $monthlyQuery->whereMonth('created_at', request('month'));
    }
    
    if(request('year')) {
        $monthlyQuery->whereYear('created_at', request('year'));
    }
    
    if(request('shop')) {
        $monthlyQuery->where('credit_shop_id', request('shop'));
    }
    
    $monthlyTotals = $monthlyQuery->get();
    
    // Invoices Query
    $invoiceQuery = CreditInvoice::with('creditShop')
        ->where('user_id', $userId);
    
    if(request('search')) {
        $invoiceQuery->where(function($q) {
            $q->where('invoice_number', 'like', '%'.request('search').'%')
              ->orWhere('customer_name', 'like', '%'.request('search').'%')
              ->orWhere('customer_phone', 'like', '%'.request('search').'%')
              ->orWhereHas('creditShop', function($q) {
                  $q->where('name', 'like', '%'.request('search').'%');
              });
        });
    }
    
    if(request('status')) {
        $invoiceQuery->where('status', request('status'));
    }
    
    if(request('invoice_shop')) {
        $invoiceQuery->where('credit_shop_id', request('invoice_shop'));
    }
    
    $invoices = $invoiceQuery->orderBy('created_at', 'desc')->paginate(10);
    
    $cashiers = Cashier::where('user_id', Auth::id())->get();
    $shopDetail = MyShopDetail::where('user_id', $userId)->first();
    $shopName = $shopDetail ? $shopDetail->shop_name : 'My Shop';
    $creditShops = CreditShop::where('user_id', $userId)->get();
    
    return view('user.credit_invoices.index', compact(
        'invoices', 
        'cashiers', 
        'shopName',
        'creditShops',
        'monthlyTotals'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'credit_shop_id' => 'required|exists:credit_shops,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'sales_rep' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'description.*' => 'required|string',
            'warranty.*' => 'nullable|string',
            'qty.*' => 'required|numeric|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        for ($i = 0; $i < count($request->description); $i++) {
            $totalAmount += $request->qty[$i] * $request->unit_price[$i];
        }

        $invoice = CreditInvoice::create([
            
            'credit_shop_id' => $request->credit_shop_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'sales_rep' => $request->sales_rep,
            'issue_date' => $request->issue_date,
            'total_amount' => $totalAmount,
            'remaining_amount' => $totalAmount,
            'user_id' => Auth::id(),
        ]);

        for ($i = 0; $i < count($request->description); $i++) {
            CreditInvoiceItem::create([
                'credit_invoice_id' => $invoice->id,
                'description' => $request->description[$i],
                'warranty' => $request->warranty[$i] ?? null,
                'quantity' => $request->qty[$i],
                'unit_price' => $request->unit_price[$i],
                'amount' => $request->qty[$i] * $request->unit_price[$i],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('user.credit_invoices.print', $invoice->id)
            ->with('success', 'Credit invoice created successfully');
    }

    public function show(CreditInvoice $creditInvoice)
    {
        return view('user.credit_invoices.show', compact('creditInvoice'));
    }

    public function download(CreditInvoice $creditInvoice)
    {
        $user = auth()->user();
        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoPath = null;
        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (!file_exists($logoPath)) {
                $logoPath = null;
            }
        }

        $viewName = $creditInvoice->items->count() > 10 
            ? 'user.credit_invoices.fullpdf' 
            : 'user.credit_invoices.pdf';

        $pdf = PDF::loadView($viewName, [
            'invoice' => $creditInvoice,
            'logoPath' => $logoPath,
            'shopDetail' => $shopDetail,
        ]);

        $pdf->setPaper([0, 0, 595.28, 421.26], 'landscape');

        return $pdf->download('credit-invoice-' . $creditInvoice->invoice_number . '.pdf');
    }

    public function print(CreditInvoice $creditInvoice)
    {
        $user = auth()->user();
        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoBase64 = null;
        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (file_exists($logoPath)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));
            }
        }

        $viewName = $creditInvoice->items->count() > 10 
            ? 'user.credit_invoices.fullprint' 
            : 'user.credit_invoices.print';

        return view($viewName, compact('creditInvoice', 'logoBase64', 'shopDetail'));
    }

    public function destroy(CreditInvoice $creditInvoice)
    {
        $creditInvoice->delete();
        return redirect()->route('user.credit_invoices.index')
            ->with('success', 'Credit invoice deleted successfully');
    }
}