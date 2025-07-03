<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['shop_name', 'phone_number', 'user_id'];

    public function items()
    {
        return $this->hasMany(ShopItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}