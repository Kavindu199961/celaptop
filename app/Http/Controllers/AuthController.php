<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\InvoiceCounter;
use App\Models\NoteCounter;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            return back()->with('error', 'Your account has been deactivated.');
        }

        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.users.index');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    return back()->with('error', 'Invalid credentials');
}

    public function showRegister()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',       // default role
        'is_active' => 0,       // default inactive
    ]);


    return redirect()->route('login')->with('success', 'Registered successfully. Please wait for admin approval.');
}


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}