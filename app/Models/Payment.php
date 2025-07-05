<?php

// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'amount', 'payment_method', 
        'bank_name', 'account_number', 'slip_path', 'remarks', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
