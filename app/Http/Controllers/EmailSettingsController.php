<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class EmailSettingsController extends Controller
{
    public function index()
    {
        return view('user.email_setting.index', [
            'email' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'from_name' => env('MAIL_FROM_NAME')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'from_name' => 'required'
        ]);

        $envPath = base_path('.env');
        
        $envContent = File::get($envPath);
        
        // Update the values
        $envContent = preg_replace('/MAIL_USERNAME=(.*)/', 'MAIL_USERNAME='.$request->email, $envContent);
        $envContent = preg_replace('/MAIL_PASSWORD=(.*)/', 'MAIL_PASSWORD='.$request->password, $envContent);
        $envContent = preg_replace('/MAIL_FROM_NAME=(.*)/', 'MAIL_FROM_NAME="'.$request->from_name.'"', $envContent);
        $envContent = preg_replace('/MAIL_FROM_ADDRESS=(.*)/', 'MAIL_FROM_ADDRESS='.$request->email, $envContent);
        
        File::put($envPath, $envContent);
        
        // Clear cache
        Artisan::call('config:clear');
        
        return back()->with('success', 'Email settings updated successfully!');
    }
}