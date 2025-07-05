<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
 public function dashboard()
{
    $search = request('search');
    $users = User::where('role', '!=', 'super-admin')
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        })
        ->paginate(10);

    return view('super-admin.users.index', compact('users'));
}

public function editUser(User $user)
{
    return response()->json($user);
}

    public function storeUser(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'is_active' => 'nullable', // Checkbox sends value only if checked
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? 1 : 0,
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
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('super-admin.users.index')->with('success', 'User updated successfully!');
    
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