<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MyShopDetail;
use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{

public function index()
{
    $search = request('search');
    $userId = Auth::id();

    $invoices = Invoice::where('user_id', $userId)
        ->when($search, function ($query) use ($search) {
            $query->where('invoice_number', 'like', '%' . $search . '%')
                ->orWhere('customer_name', 'like', '%' . $search . '%')
                ->orWhere('customer_phone', 'like', '%' . $search . '%')
                ->orWhere('sales_rep', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Get the cashier details for the logged-in user
    $cashiers = Cashier::where('user_id', Auth::id())->get();
    $shopDetail = MyShopDetail::where('user_id', $userId)->first();
    $shopName = $shopDetail ? $shopDetail->shop_name : 'My Shop';


    return view('user.invoices.index', compact('invoices', 'cashiers', 'shopName'));
}


    public function create()
    {
        return view('user.invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
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

        $invoice = Invoice::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'sales_rep' => $request->sales_rep,
            'issue_date' => $request->issue_date,
            'total_amount' => $totalAmount,
            'user_id' => Auth::id(),
        ]);

        for ($i = 0; $i < count($request->description); $i++) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $request->description[$i],
                'warranty' => $request->warranty[$i] ?? null,
                'quantity' => $request->qty[$i],
                'unit_price' => $request->unit_price[$i],
                'amount' => $request->qty[$i] * $request->unit_price[$i],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('user.invoices.print', $invoice->id)
            ->with('success', 'Invoice created successfully');
    }

    public function show(Invoice $invoice)
{
    // Simply check if user is logged in (handled by middleware)
    // No additional ownership check
    return view('user.invoices.show', compact('invoice'));
}

  public function download(Invoice $invoice)
{
    // Ensure the user is authenticated
    $user = auth()->user();
    if (!$user) {
        abort(403, 'Unauthorized access');
    }

    // Get shop details for the authenticated user
    $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

    // Check if logo exists and is readable
    $logoPath = null;
    if ($shopDetail && $shopDetail->logo_image) {
        $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
        if (!file_exists($logoPath) || !is_readable($logoPath)) {
            \Log::error("Logo file not found or not readable: " . $logoPath);
            $logoPath = null;
        }
    }

    // Choose PDF view based on number of items
    $viewName = $invoice->items->count() > 10 
        ? 'user.invoices.fullpdf' 
        : 'user.invoices.pdf';

    // Generate PDF
    $pdf = PDF::loadView($viewName, [
        'invoice' => $invoice,
        'logoPath' => $logoPath,
        'shopDetail' => $shopDetail,
    ]);

    // Set paper size (A2 landscape)
    $pdf->setPaper([0, 0, 595.28, 421.26]);

    // Download the generated PDF
    return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
}


    public function print(Invoice $invoice)
{
    $user = auth()->user();
    if (!$user) {
        abort(403, 'Unauthorized access');
    }
    // Assuming a relationship: $user->shopDetail
    $shopDetail =  MyShopDetail::where('user_id', $user->id)->first();

    $logoBase64 = null;

    if ($shopDetail && $shopDetail->logo_image) {
        $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = base64_encode($logoData);
        }
    }

    $viewName = $invoice->items->count() > 10 
        ? 'user.invoices.fullprint' 
        : 'user.invoices.print';

    return view($viewName, compact('invoice', 'logoBase64', 'shopDetail'));
}

    public function destroy(Invoice $invoice)
    {
       
        $invoice->delete();
        return redirect()->route('user.invoices.index')->with('success', 'Invoice deleted successfully');
    }

        protected function authorizeAccess(Invoice $invoice)
{
    if (auth()->user()->cannot('view', $invoice)) {
        abort(403, 'Unauthorized action.');
    }
}
}
