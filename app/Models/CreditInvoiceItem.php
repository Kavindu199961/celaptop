<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditInvoiceItem extends Model
{
    protected $fillable = [
        'credit_invoice_id',
        'description',
        'warranty',
        'quantity',
        'unit_price',
        'amount',
        'user_id'
    ];

    public function creditInvoice(): BelongsTo
    {
        return $this->belongsTo(CreditInvoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}