<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $fillable = [
        'item_name',
        'description',
        'cost',
        'whole_sale_price',
        'retail_price',
        'vender',
        'stock_date',
        'quantity',
        'user_id' // Added user_id to track the owner of the stock item
    ];

    protected $dates = [
        'stock_date',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}