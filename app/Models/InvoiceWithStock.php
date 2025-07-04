<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceCounter;

class InvoiceWithStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_phone',
        'sales_rep',
        'issue_date',
        'total_amount',
        'user_id'
    ];

    protected $dates = [
        'issue_date',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceWithStockItem::class);
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($invoice) {
        $userId = Auth::id(); // Current user

        // Find or create counter for the user
        $counter = InvoiceCounter::firstOrCreate(
            ['user_id' => $userId],
            ['last_number' => 0]
        );

        // Get shop details and create prefix
        $shop = MyShopDetail::where('user_id', $userId)->first();
        $prefix = $shop && $shop->shop_name 
                    ? strtoupper(substr(preg_replace('/\s+/', '', $shop->shop_name), 0, 2)) 
                    : 'XX';

        // Increment and save
        $counter->last_number += 1;
        $counter->save();

        // Set invoice number with shop prefix
        $invoice->invoice_number = $prefix . '-INV-' . str_pad($counter->last_number, 4, '0', STR_PAD_LEFT);
        $invoice->user_id = $userId;
    });
}
}