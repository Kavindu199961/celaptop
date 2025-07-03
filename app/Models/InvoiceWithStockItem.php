<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceWithStockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_with_stock_id',
        'stock_id',
        'description',
        'warranty',
        'quantity',
        'unit_price',
        'amount',
        'user_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(InvoiceWithStock::class, 'invoice_with_stock_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}