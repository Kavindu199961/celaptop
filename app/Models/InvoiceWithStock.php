<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}