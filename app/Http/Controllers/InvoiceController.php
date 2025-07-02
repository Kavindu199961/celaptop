<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $search = request('search');

        $invoices = Invoice::when($search, function($query) use ($search) {
                $query->where('invoice_number', 'like', '%'.$search.'%')
                      ->orWhere('customer_name', 'like', '%'.$search.'%')
                      ->orWhere('customer_phone', 'like', '%'.$search.'%')
                      ->orWhere('sales_rep', 'like', '%'.$search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('admin.invoices.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'sales_rep' => 'required|string|max:255',
        'issue_date' => 'required|date',
        'description.*' => 'required|string',
        'warranty.*' => 'nullable|string',
        'qty.*' => 'required|numeric|min:1',
        'unit_price.*' => 'required|numeric|min:0',
    ]);

    // Calculate total amount
    $totalAmount = 0;
    for ($i = 0; $i < count($request->description); $i++) {
        $totalAmount += $request->qty[$i] * $request->unit_price[$i];
    }

    // Create invoice
    $invoice = Invoice::create([
        'invoice_number' => 'INV-' . str_pad(Invoice::max('id') + 1, 6, '0', STR_PAD_LEFT),
        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone,
        'sales_rep' => $request->sales_rep,
        'issue_date' => $request->issue_date,
        'total_amount' => $totalAmount,
    ]);

    // Create invoice items
    for ($i = 0; $i < count($request->description); $i++) {
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => $request->description[$i],
            'warranty' => $request->warranty[$i] ?? null,
            'quantity' => $request->qty[$i],
            'unit_price' => $request->unit_price[$i],
            'amount' => $request->qty[$i] * $request->unit_price[$i],
        ]);
    }

    // Redirect to print page instead of downloading PDF
    return redirect()->route('admin.invoices.print', $invoice->id)
        ->with('success', 'Invoice created successfully');
}


    public function show(Invoice $invoice)
    {
        return view('admin.invoices.show', compact('invoice'));
    }



    public function download(Invoice $invoice)
{
    // Add logo path handling
    $logoPath = public_path('assets/logo/logo1.jpg');
    
    // Convert logo to base64 for PDF embedding
    $logoBase64 = null;
    if (file_exists($logoPath)) {
        $logoData = file_get_contents($logoPath);
        $logoBase64 = base64_encode($logoData);
    }

    // Determine which view to use based on the number of items
    $viewName = ($invoice->items->count() > 10) 
        ? 'admin.invoices.fullpdf' 
        : 'admin.invoices.pdf';

    $pdf = PDF::loadView($viewName, [
        'invoice' => $invoice,
        'logoBase64' => $logoBase64
    ]);
    
    return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
}

 public function print(Invoice $invoice)
{
    // Convert logo to base64
    $logoPath = public_path('/assets/logo/logo1.jpg');
    $logoBase64 = null;

    if (file_exists($logoPath)) {
        $logoData = file_get_contents($logoPath);
        $logoBase64 = base64_encode($logoData);
    }

    // Determine which view to use based on item count
    $viewName = $invoice->items->count() > 10 
        ? 'admin.invoices.fullprint' 
        : 'admin.invoices.print';

    return view($viewName, compact('invoice', 'logoBase64'));
}


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
    }
}