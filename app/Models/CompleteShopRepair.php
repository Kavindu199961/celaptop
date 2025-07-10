<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompleteShopRepair extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_item_id',
        'shop_id',
        'user_id',
        'final_price',
        'notes',
        'status'
    ];

    public function repairItem()
    {
        return $this->belongsTo(RepairItem::class);
    }

    public function shop()
    {
        return $this->belongsTo(ShopNames::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}