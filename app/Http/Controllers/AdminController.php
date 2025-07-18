<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Define all available permissions
    protected $allPermissions = [
    'Invoice',
    'Invoice With Stock',
    'Estimates',
    'Stock',
    'Credit Shops',
    'Credit Invoices',
    'Invoice Report',
    'Laptop Repair',
    'Completed Repair',
    'Shop Repair Details',
    'Completed Shop Repairs',
    'Email Setting',
];



   public function dashboard()
{
    $search = request('search');
    $users = User::where('role', '!=', 'super-admin')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        })
        ->paginate(10);

    return view('super-admin.users.index', [
        'users' => $users,
        'allPermissions' => $this->allPermissions // Pass permissions to view
    ]);
}

    public function editUser(User $user)
    {
        return response()->json([
            'user' => $user,
            'allPermissions' => $this->allPermissions
        ]);
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,user',
                'is_active' => 'nullable',
                'permissions' => 'nullable|array',
                'permissions.*' => 'string'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'permissions' => $request->permissions ?? []
            ]);

            return redirect()->route('super-admin.users.index')->with('success', 'User created successfully!');
        
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An unexpected error occurred. Please check logs.']);
        }
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }

 public function updateUser(Request $request, User $user)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'is_active' => 'nullable',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string'
        ]);

        // Update basic user info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->has('is_active') ? 1 : 0;

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $user->password = Hash::make($request->password);
        }

        // Explicitly set permissions
        $user->permissions = $request->permissions ?? [];

        // Save the user
        $user->save();

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User updated successfully!');
    
    } catch (\Exception $e) {
        Log::error('User update error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'An unexpected error occurred while updating the user.']);
    }
}

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('super-admin.users.index')->with('success', 'User deleted successfully!');
    }
}