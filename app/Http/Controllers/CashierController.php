<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $search = request('search');
        $userId = Auth::id();

        $cashiers = Cashier::where('user_id', $userId)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.cashier.index', compact('cashiers'));
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $validated['user_id'] = Auth::id();

            Cashier::create($validated);

            return redirect()->route('user.cashier.index')
                ->with('success', 'Cashier created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check the form fields.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function edit($id)
    {
        $cashier = Cashier::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (request()->ajax()) {
            return response()->json($cashier);
        }

        return view('user.cashier.edit', compact('cashier'));
    }

    public function update(Request $request, $id)
    {
        $cashier = Cashier::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $cashier->update($validated);

            return redirect()->route('user.cashier.index')
                ->with('success', 'Cashier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the cashier: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $cashier = Cashier::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $cashierName = $cashier->name;
            $cashier->delete();

            return redirect()->route('user.cashier.index')
                ->with('success', 'Cashier (' . $cashierName . ') deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.cashier.index')
                ->with('error', 'Failed to delete cashier: ' . $e->getMessage());
        }
    }
}