<?php

namespace App\Http\Controllers;

use App\Models\LaptopRepair;
use App\Models\CompletedRepair;
use Illuminate\Http\Request;
use App\Models\NoteCounter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MyShopDetail;
use App\Models\Counter;
use Illuminate\Support\Facades\Mail;
use App\Mail\RepairDetailsMail;
use App\Services\UserMailService;

class LaptopRepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $search = request('search');

    // Get the current note counter value
    $noteCounter = NoteCounter::where('user_id', Auth::id())->first();
    $lastNoteNumber = $noteCounter ? $noteCounter->last_number : 0;
    $nextNoteNumber = $lastNoteNumber + 1;

    // Get repairs for the authenticated user
    $repairs = LaptopRepair::where('user_id', Auth::id())
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_number', 'like', '%'.$search.'%')
                  ->orWhere('customer_name', 'like', '%'.$search.'%')
                  ->orWhere('serial_number', 'like', '%'.$search.'%')
                  ->orWhere('note_number', 'like', '%'.$search.'%')
                  ->orWhere('device', 'like', '%'.$search.'%');
            });
        })
        ->latest()
        ->paginate(10);

    return view('user.laptop-repair.index', compact('repairs', 'lastNoteNumber', 'nextNoteNumber'));
}

/**
 * Generate a unique customer number for a new repair.
 */
protected function generateCustomerNumber()
{
    // Example: Use current timestamp and user ID for uniqueness
    $userId = auth()->id();

    // Fetch shop details for the current user
    $shop = MyShopDetail::where('user_id', $userId)->first();

    // Get first 2 letters of shop name as prefix, fallback to 'XX'
    $prefix = $shop && $shop->shop_name 
                ? strtoupper(substr(preg_replace('/\s+/', '', $shop->shop_name), 0, 2)) 
                : 'XX';

    // Get unique counter for this user
    $newNumber = Counter::incrementAndGet('customer_number_user_' . $userId);

    // Format: [SHOPPREFIX]-[USERID]-[SEQUENCE]
    return $prefix . '-' . $userId . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}

   
// Fixed store function
public function store(Request $request, UserMailService $mailService)
{
    try {
        // Validate request data
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'device' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255', // Removed the unique validation
            'fault' => 'required|string',
            'repair_price' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'note_number' => 'required|string|max:255|unique:laptop_repairs,note_number',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'ram' => 'nullable|in:4GB,8GB,12GB,16GB,32GB,64GB',
            'hdd' => 'nullable|boolean',
            'ssd' => 'nullable|boolean',
            'nvme' => 'nullable|boolean',
            'battery' => 'nullable|boolean',
            'dvd_rom' => 'nullable|boolean',
            'keyboard' => 'nullable|boolean',
        ]);

        // Rest of your code remains the same...
        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? 'pending';
        
        // Add authenticated user ID
        $validated['user_id'] = Auth::id();
        
        // Generate customer number
        $validated['customer_number'] = $this->generateCustomerNumber();

        // Handle boolean fields
        $validated['hdd'] = $request->has('hdd');
        $validated['ssd'] = $request->has('ssd');
        $validated['nvme'] = $request->has('nvme');
        $validated['battery'] = $request->has('battery');
        $validated['dvd_rom'] = $request->has('dvd_rom');
        $validated['keyboard'] = $request->has('keyboard');

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('repairs', 'public');
                    $imagePaths[] = $path;
                }
            }
            $validated['images'] = json_encode($imagePaths);
        }

        // Create repair record
        $repair = LaptopRepair::create($validated);

        // Send email notification if recipient email exists
        if (!empty($validated['email'])) {
            try {
                $user = Auth::user();
                
                if (!$user || !$user->email) {
                    throw new \RuntimeException('Invalid authenticated user');
                }

                $mailable = (new RepairDetailsMail($repair))
                    ->to($validated['email']);
                    
                \Log::debug('Attempting to send email', [
                    'recipient' => $validated['email'],
                    'user_email' => $user->email,
                    'user_id' => $user->id
                ]);

                if ($mailService->sendWithUserConfig($user, $mailable)) {
                    \Log::info('Email sent successfully');
                } else {
                    \Log::warning('Email sent using fallback method');
                    session()->flash('warning', 'Email sent using default system mailer');
                }
                
            } catch (\Exception $e) {
                \Log::error('Email sending failed', [
                    'error' => $e->getMessage(),
                    'repair_id' => $repair->id ?? null,
                    'trace' => $e->getTraceAsString()
                ]);
                session()->flash('warning', 'Email could not be sent: ' . $e->getMessage());
            }
        }

        return redirect()->route('user.laptop-repair.index')
            ->with('success', "Repair #{$repair->note_number} created successfully");

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Validation errors occurred');

    } catch (\Exception $e) {
        \Log::error("Repair creation failed: {$e->getMessage()}");
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating repair: ' . $e->getMessage());
    }
}
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
        $repair->images = json_decode($repair->images, true);
        return view('user.laptop-repair.show', compact('repair'));
    }

   
   public function edit($id)
{
   
    $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
    // Convert images JSON to array if it exists
    $repair->images = $repair->images ? json_decode($repair->images, true) : [];
    
    return response()->json($repair);
}

public function update(Request $request, $id)
{
    try {
        $repair = LaptopRepair::findOrFail($id);

        // Validate request data
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'device' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'fault' => 'required|string',
            'repair_price' => 'nullable|numeric|min:0',
            'date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'note_number' => 'required|string|max:255|unique:laptop_repairs,note_number,'.$repair->id,
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'ram' => 'nullable|in:4GB,8GB,12GB,16GB,32GB,64GB',
            'hdd' => 'nullable|boolean',
            'ssd' => 'nullable|boolean',
            'nvme' => 'nullable|boolean',
            'battery' => 'nullable|boolean',
            'dvd_rom' => 'nullable|boolean',
            'keyboard' => 'nullable|boolean',
            'existing_images' => 'nullable|array',
        ]);

        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? $repair->status;
        
        // Handle boolean fields
        $validated['hdd'] = $request->has('hdd');
        $validated['ssd'] = $request->has('ssd');
        $validated['nvme'] = $request->has('nvme');
        $validated['battery'] = $request->has('battery');
        $validated['dvd_rom'] = $request->has('dvd_rom');
        $validated['keyboard'] = $request->has('keyboard');

        // Handle image uploads
        $existingImages = $request->input('existing_images', []);
        $newImagePaths = [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('repairs', 'public');
                    $newImagePaths[] = $path;
                }
            }
        }
        
        // Combine existing and new images
        $allImages = array_merge($existingImages, $newImagePaths);
        $validated['images'] = !empty($allImages) ? json_encode($allImages) : null;

        // Update repair record
        $repair->update($validated);

        // Return JSON response with redirect URL for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Repair #{$repair->note_number} updated successfully",
                'redirect_url' => route('user.laptop_repair.index')
            ]);
        }

        // Redirect for normal form submissions
        return redirect()->route('user.laptop-repair.index')
                         ->with('success', "Repair #{$repair->note_number} updated successfully");

    } catch (\Illuminate\Validation\ValidationException $e) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $e->validator->errors()
            ], 422);
        }
        return back()->withErrors($e->validator)->withInput();

    } catch (\Exception $e) {
        \Log::error("Repair update failed: {$e->getMessage()}");
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating repair: ' . $e->getMessage()
            ], 500);
        }
        return back()->with('error', 'Error updating repair: ' . $e->getMessage());
    }
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
            $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
            $oldStatus = $repair->status;
            
            if ($request->status == 'completed' && $repair->status != 'completed') {
                $repair->update(['status' => 'completed']);
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Repair marked as completed',
                    'new_status' => 'completed',
                    'show_price_modal' => true,
                    'old_status' => $oldStatus
                ]);
            }
            
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
            $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
            
            // Update the price first
            $repair->update(['repair_price' => $request->repair_price]);
            
            // Now move to completed_repairs table
            $completedRepair = $repair->replicate();
            $completedRepair->completed_at = now();
            $completedRepair->user_id = Auth::id(); // Keep user association
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
            $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
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
     * Get repair statistics for the authenticated user
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => LaptopRepair::where('user_id', Auth::id())->count(),
                'pending' => LaptopRepair::where('user_id', Auth::id())->where('status', 'pending')->count(),
                'in_progress' => LaptopRepair::where('user_id', Auth::id())->where('status', 'in_progress')->count(),
                'completed' => LaptopRepair::where('user_id', Auth::id())->where('status', 'completed')->count(),
                'cancelled' => LaptopRepair::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
                'total_revenue' => LaptopRepair::where('user_id', Auth::id())->where('status', 'completed')->sum('repair_price')
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
            $repair = LaptopRepair::where('user_id', Auth::id())->findOrFail($id);
            $customerNumber = $repair->customer_number;
            $repair->delete();

            return redirect()->route('user.laptop-repair.index')
                             ->with('success', 'Repair record (' . $customerNumber . ') deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('user.laptop-repair.index')
                             ->with('error', 'Failed to delete repair record: ' . $e->getMessage());
        }
    }

   
}