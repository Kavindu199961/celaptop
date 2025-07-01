<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'item_name',
        'description',
        'warranty',
        'serial_number',
        'price',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}