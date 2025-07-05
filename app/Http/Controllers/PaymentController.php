<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
   public function index()
{
    $search = request('search');
    $status = request('status');
    $method = request('method');
    $fromDate = request('from_date');
    $toDate = request('to_date');

    $payments = Payment::with('user')
        ->when($search, function ($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                              ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhere('bank_name', 'like', '%' . $search . '%')
                ->orWhere('account_number', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        })
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($method, function ($query) use ($method) {
            $query->where('payment_method', $method);
        })
        ->when($fromDate, function ($query) use ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        })
        ->when($toDate, function ($query) use ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('super-admin.payments.index', compact('payments'));
}


    public function approve(Payment $payment)
    {
        $payment->update(['status' => 'approved']);
        
        // Here you can add notification to user
        
        return redirect()->back()->with('success', 'Payment approved successfully!');
    }

    public function reject(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'rejected',
            'remarks' => $request->reject_reason 
                ? $payment->remarks . "\nRejection Reason: " . $request->reject_reason 
                : $payment->remarks
        ]);
        
        // Here you can add notification to user
        
        return redirect()->back()->with('success', 'Payment rejected successfully!');
    }
}