<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class EmailSettingController extends Controller
{
    public function index()
    {
        return view('user.email_setting.index');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_encryption' => 'required|in:tls,ssl',
            'email_username' => 'required|string|max:255',
            'email_password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $data = [
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_encryption' => $request->smtp_encryption,
            'email_username' => $request->email_username,
        ];

        // Only update password if provided
        if ($request->filled('email_password')) {
            $data['email_password'] = Crypt::encryptString($request->email_password);
        }

        $user->update($data);

        return redirect()->route('user.email-settings.index')
            ->with('success', 'Email settings updated successfully!');
    }
}