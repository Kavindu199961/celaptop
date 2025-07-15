<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\EstimateItem;
use App\Models\MyShopDetail;
use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EstimateController extends Controller
{
    public function index()
    {
        $search = request('search');
        $userId = Auth::id();

        $estimates = Estimate::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('estimate_number', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%')
                    ->orWhere('customer_phone', 'like', '%' . $search . '%')
                    ->orWhere('sales_rep', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cashiers = Cashier::where('user_id', Auth::id())->get();
        $shopDetail = MyShopDetail::where('user_id', $userId)->first();
        $shopName = $shopDetail ? $shopDetail->shop_name : 'My Shop';

        return view('user.estimates.index', compact('estimates', 'cashiers', 'shopName'));
    }

    public function create()
    {
        return view('user.estimates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'sales_rep' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'description.*' => 'required|string',
            'warranty.*' => 'nullable|string',
            'qty.*' => 'required|numeric|min:1',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        for ($i = 0; $i < count($request->description); $i++) {
            $totalAmount += $request->qty[$i] * $request->unit_price[$i];
        }

        $estimate = Estimate::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'sales_rep' => $request->sales_rep,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'total_amount' => $totalAmount,
            'user_id' => Auth::id(),
        ]);

        for ($i = 0; $i < count($request->description); $i++) {
            EstimateItem::create([
                'estimate_id' => $estimate->id,
                'description' => $request->description[$i],
                'warranty' => $request->warranty[$i] ?? null,
                'quantity' => $request->qty[$i],
                'unit_price' => $request->unit_price[$i],
                'amount' => $request->qty[$i] * $request->unit_price[$i],
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('user.estimates.print', $estimate->id)
            ->with('success', 'Estimate created successfully');
    }

    public function show(Estimate $estimate)
    {
        return view('user.estimates.show', compact('estimate'));
    }

    public function download(Estimate $estimate)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoPath = null;
        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (!file_exists($logoPath) || !is_readable($logoPath)) {
                \Log::error("Logo file not found or not readable: " . $logoPath);
                $logoPath = null;
            }
        }

        $viewName = $estimate->items->count() > 10 
            ? 'user.estimates.fullpdf' 
            : 'user.estimates.pdf';

        $pdf = PDF::loadView($viewName, [
            'estimate' => $estimate,
            'logoPath' => $logoPath,
            'shopDetail' => $shopDetail,
        ]);

        $pdf->setPaper([0, 0, 595.28, 421.26]);

        return $pdf->download('estimate-' . $estimate->estimate_number . '.pdf');
    }

    public function print(Estimate $estimate)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized access');
        }

        $shopDetail = MyShopDetail::where('user_id', $user->id)->first();

        $logoBase64 = null;
        if ($shopDetail && $shopDetail->logo_image) {
            $logoPath = storage_path('app/public/' . $shopDetail->logo_image);
            if (file_exists($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoBase64 = base64_encode($logoData);
            }
        }

        $viewName = $estimate->items->count() > 10 
            ? 'user.estimates.fullprint' 
            : 'user.estimates.print';

        return view($viewName, compact('estimate', 'logoBase64', 'shopDetail'));
    }

    public function destroy(Estimate $estimate)
    {
        $estimate->delete();
        return redirect()->route('user.estimates.index')->with('success', 'Estimate deleted successfully');
    }

    protected function authorizeAccess(Estimate $estimate)
    {
        if (auth()->user()->cannot('view', $estimate)) {
            abort(403, 'Unauthorized action.');
        }
    }
}