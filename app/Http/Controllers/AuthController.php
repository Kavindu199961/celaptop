<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Models\Payment;
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
        
        // Payment validation
        'amount' => 'required|numeric|min:1',
        'payment_method' => 'required|string|in:bank_transfer,cash_deposit',
        'bank_name' => 'required_if:payment_method,bank_transfer|nullable|string',
        'account_number' => 'required_if:payment_method,bank_transfer|nullable|string',
        'slip' => 'required_if:payment_method,bank_transfer|nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
        'remarks' => 'required|string|max:500',
        
    ], [
        'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        'slip.required_if' => 'Please upload your bank slip for bank transfer payments.',
        'slip.mimes' => 'Bank slip must be a PDF, PNG, JPG, or JPEG file.',
        'slip.max' => 'Bank slip must not exceed 2MB in size.',
        'bank_name.required_if' => 'Bank name is required for bank transfers.',
        'account_number.required_if' => 'Account number is required for bank transfers.',
    ]);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user', // Default role for new users
        'is_active' => false, // Default to inactive until payment is processed
    ]);

    // Process payment
    $paymentData = [
        'user_id' => $user->id,
        'name' => $request->name,
        'amount' => $request->amount,
        'payment_method' => $request->payment_method,
        'remarks' => $request->remarks,
        'status' => 'pending',
    ];

    if ($request->payment_method === 'bank_transfer') {
        $paymentData['bank_name'] = $request->bank_name;
        $paymentData['account_number'] = $request->account_number;
        
        if ($request->hasFile('slip')) {
            $path = $request->file('slip')->store('payment_slips', 'public');
            $paymentData['slip_path'] = $path;
        }
    }

    // Create payment record
    Payment::create($paymentData);

    event(new Registered($user));

    Auth::login($user);

   return redirect()->route('web.repair-tracking.index')->with('success', 'Registered successfully. Please wait for admin approval.');
}

    public function logout()
{
    Auth::logout();
    return redirect()->route('web.repair-tracking.index');
}

}