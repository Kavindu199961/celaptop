<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Models\InvoiceCounter;
use App\Models\MyShopDetail;

class CreditInvoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'credit_shop_id',
        'customer_name',
        'customer_phone',
        'sales_rep',
        'issue_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'user_id'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CreditInvoiceItem::class);
    }

    public function creditShop(): BelongsTo
    {
        return $this->belongsTo(CreditShop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Automatically set invoice number and user_id when creating a new CreditInvoice
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $userId = Auth::id(); // Current logged-in user

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

            // Increment and save the counter
            $counter->last_number += 1;
            $counter->save();

            // Set invoice number with shop prefix
            $invoice->invoice_number = $prefix . '-INVCR-' . str_pad($counter->last_number, 4, '0', STR_PAD_LEFT);
            $invoice->user_id = $userId;
        });
    }
}
