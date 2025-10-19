<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AccountController extends Controller
{
   public function index(Request $request)
{
    $userId = Auth::id();
    $query = Account::where('user_id', $userId)
                    ->latest();

    // Search filter
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%$search%");
        });
    }

    // Date filter
    if ($request->has('date') && $request->date) {
        $query->whereDate('date', $request->date);
    }

    // Date range filter
    if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    // Month filter
    if ($request->has('month') && $request->month) {
        $query->whereMonth('date', $request->month);
    }

    // Year filter
    if ($request->has('year') && $request->year) {
        $query->whereYear('date', $request->year);
    }

    $accounts = $query->paginate(10);

    // Calculate summary data
    $summaryQuery = Account::where('user_id', $userId);
    
    // Apply the same filters to summary query
    if ($request->has('date') && $request->date) {
        $summaryQuery->whereDate('date', $request->date);
    }
    if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
        $summaryQuery->whereBetween('date', [$request->start_date, $request->end_date]);
    }
    if ($request->has('month') && $request->month) {
        $summaryQuery->whereMonth('date', $request->month);
    }
    if ($request->has('year') && $request->year) {
        $summaryQuery->whereYear('date', $request->year);
    }

    $totalIncome = (clone $summaryQuery)->where('amount', '>', 0)->sum('amount');
    $totalExpense = (clone $summaryQuery)->where('amount', '<', 0)->sum('amount');
    $netBalance = $totalIncome + $totalExpense;

    return view('user.account.index', compact('accounts', 'totalIncome', 'totalExpense', 'netBalance'));
}

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Account::create([
            'user_id' => Auth::id(),
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('user.account.index')->with('success', 'Account record added successfully');
    }

    public function edit($id)
    {
        $account = Account::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return response()->json($account);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        $account = Account::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        $account->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('user.account.index')->with('success', 'Account record updated successfully');
    }

    public function destroy($id)
    {
        $account = Account::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        $account->delete();
        return redirect()->route('user.account.index')->with('success', 'Account record deleted successfully');
    }

    public function show($id)
    {
        $account = Account::where('id', $id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return view('user.account.show', compact('account'));
    }


public function exportPdf(Request $request)
{
    $userId = Auth::id();
    $query = Account::where('user_id', $userId);

    // Apply the same filters as index method
    if ($request->has('search')) {
        $search = $request->search;
        $query->where('description', 'like', "%$search%");
    }

    if ($request->has('date') && $request->date) {
        $query->whereDate('date', $request->date);
    }

    if ($request->has('start_date') && $request->start_date && $request->has('end_date') && $request->end_date) {
        $query->whereBetween('date', [$request->start_date, $request->end_date]);
    }

    if ($request->has('month') && $request->month) {
        $query->whereMonth('date', $request->month);
    }

    if ($request->has('year') && $request->year) {
        $query->whereYear('date', $request->year);
    }

    $accounts = $query->orderBy('date', 'desc')->get();

    // Calculate totals
    $totalIncome = $accounts->where('amount', '>', 0)->sum('amount');
    $totalExpense = $accounts->where('amount', '<', 0)->sum('amount');
    $netBalance = $totalIncome + $totalExpense;

    // Generate filter info for PDF
    $filterInfo = [
        'search' => $request->search,
        'date' => $request->date,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'month' => $request->month ? DateTime::createFromFormat('!m', $request->month)->format('F') : null,
        'year' => $request->year,
    ];

    $pdf = Pdf::loadView('user.account.pdf', compact('accounts', 'totalIncome', 'totalExpense', 'netBalance', 'filterInfo'));
    
    $filename = 'account-statement-' . date('Y-m-d') . '.pdf';
    return $pdf->download($filename);
}
}