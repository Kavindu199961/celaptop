<?php

namespace App\Http\Controllers;

use App\Models\LaptopRepair;
use App\Models\CompletedRepair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaptopRepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $search = request('search');
    $lastNoteNumber = LaptopRepair::latest()->value('note_number');
    $nextNoteNumber = $lastNoteNumber ? $lastNoteNumber + 1 : 1; // Handle case when there are no records
    
    $repairs = LaptopRepair::when($search, function($query) use ($search) {
            $query->where('customer_number', 'like', '%'.$search.'%')
                  ->orWhere('customer_name', 'like', '%'.$search.'%')
                  ->orWhere('serial_number', 'like', '%'.$search.'%')
                  ->orWhere('note_number', 'like', '%'.$search.'%')
                  ->orWhere('device', 'like', '%'.$search.'%');
        })
        ->orderBy('date', 'desc')
        ->paginate(10);

    return view('admin.laptop-repair.index', compact('repairs', 'nextNoteNumber'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.laptop-repair.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    try {
        // Validation
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'device' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:laptop_repairs,serial_number',
            'fault' => 'required|string',
            'repair_price' => 'required|numeric|min:0',
            'note_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled'
        ]);
        
        // Generate note number if not provided
        if (empty($validated['note_number'])) {
            $validated['note_number'] = LaptopRepair::generateNoteNumber();
        }

        // Set default status
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        // Create the record
        $repair = LaptopRepair::create($validated);

        return redirect()->route('admin.laptop-repair.index')
                         ->with('success', 'Repair record created successfully. Customer Number: ' . $repair->customer_number);

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
}/**
     * Display the specified resource.
     */
    public function show($id)
    {
        $repair = LaptopRepair::findOrFail($id);
        return view('admin.laptop-repair.show', compact('repair'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $repair = LaptopRepair::findOrFail($id);
        return view('admin.laptop-repair.edit', compact('repair'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $repair = LaptopRepair::findOrFail($id);
        
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'device' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:laptop_repairs,serial_number,' . $id,
            'fault' => 'required|string',
            'repair_price' => 'required|numeric|min:0',
            'note_number' => 'nullable|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $repair->update($validated);

        return redirect()->route('admin.laptop-repair.index')
                         ->with('success', 'Repair record updated successfully.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        DB::beginTransaction();

        try {
            $repair = LaptopRepair::findOrFail($id);
            $oldStatus = $repair->status;
            
            // If status is being changed to completed, handle the completion flow
            if ($request->status == 'completed' && $repair->status != 'completed') {
                // Don't move to completed table yet, just update status
                // The frontend will handle price confirmation, then call the completion method
                $repair->update(['status' => 'completed']);
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Repair marked as completed',
                    'new_status' => 'completed',
                    'show_price_modal' => true, // Flag to show price confirmation modal
                    'old_status' => $oldStatus
                ]);
            }
            
            // For other status changes
            $repair->update(['status' => $request->status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $repair->status
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete the repair and move to completed_repairs table
     */
    public function completeRepair(Request $request, $id)
    {
        $request->validate([
            'repair_price' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            $repair = LaptopRepair::findOrFail($id);
            
            // Update the price first
            $repair->update(['repair_price' => $request->repair_price]);
            
            // Now move to completed_repairs table
            $completedRepair = $repair->replicate();
            $completedRepair->completed_at = now();
            $completedRepair->setTable('completed_repairs');
            $completedRepair->save();
            
            // Remove from original table
            $repair->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Repair completed successfully and moved to completed repairs',
                'redirect' => true
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete repair: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the price of the specified resource.
     */
    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'repair_price' => 'required|numeric|min:0'
        ]);

        try {
            $repair = LaptopRepair::findOrFail($id);
            $repair->update(['repair_price' => $request->repair_price]);

            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully',
                'new_price' => number_format($repair->repair_price, 2)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update price: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get repair statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => LaptopRepair::count(),
                'pending' => LaptopRepair::where('status', 'pending')->count(),
                'in_progress' => LaptopRepair::where('status', 'in_progress')->count(),
                'completed' => LaptopRepair::where('status', 'completed')->count(),
                'cancelled' => LaptopRepair::where('status', 'cancelled')->count(),
                'total_revenue' => LaptopRepair::where('status', 'completed')->sum('repair_price')
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $repair = LaptopRepair::findOrFail($id);
            $customerNumber = $repair->customer_number;
            $repair->delete();

            return redirect()->route('admin.laptop-repair.index')
                             ->with('success', 'Repair record (' . $customerNumber . ') deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.laptop-repair.index')
                             ->with('error', 'Failed to delete repair record: ' . $e->getMessage());
        }
    }
}