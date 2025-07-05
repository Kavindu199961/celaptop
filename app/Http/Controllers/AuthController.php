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

        // Validate only real-looking email addresses (no fake domains like test.com, mailinator, etc.)
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users,email',
            function ($attribute, $value, $fail) {
                $disposableDomains = ['mailinator.com', 'tempmail.com', '10minutemail.com', 'example.com', 'test.com'];
                $domain = substr(strrchr($value, "@"), 1);

                if (in_array(strtolower($domain), $disposableDomains)) {
                    $fail('Please use a valid, non-disposable email address.');
                }
            },
        ],

        // Strong password validation
        'password' => [
            'required',
            'string',
            'min:8',             // Minimum 8 characters
            'regex:/[a-z]/',      // At least one lowercase letter
            'regex:/[A-Z]/',      // At least one uppercase letter
            'regex:/[0-9]/',      // At least one digit
            'regex:/[@$!%*#?&]/', // At least one special character
            'confirmed',
        ],
    ], [
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
        'is_active' => 0,
    ]);

    return redirect()->route('login')->with('success', 'Registered successfully. Please wait for admin approval.');
}


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}