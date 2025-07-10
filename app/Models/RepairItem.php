<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShopNames; // Make sure to import the ShopNames model

class RepairItem extends Model
{
    use HasFactory;

    protected $fillable = [
         'item_number',
        'shop_id',
        'item_name',
        'price',
        'description',
        'serial_number',
        'date',
        'status',
        'ram',
        'hdd',
        'ssd',
        'nvme',
        'battery',
        'dvd_rom',
        'keyboard'
    ];

     protected $casts = [
        'hdd' => 'boolean',
        'ssd' => 'boolean',
        'nvme' => 'boolean',
        'battery' => 'boolean',
        'dvd_rom' => 'boolean',
        'keyboard' => 'boolean',
        'date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                // Generate item number when creating new repair item
                $shop = ShopNames::findOrFail($model->shop_id);
                $shopInitials = strtoupper(substr($shop->name, 0, 2));
                
                // Get the last item number for this shop
                $lastItem = RepairItem::where('shop_id', $model->shop_id)
                    ->orderBy('id', 'desc')
                    ->first();
                    
                // Calculate the next increment
                $increment = 1;
                if ($lastItem && $lastItem->item_number) {
                    $parts = explode('-', $lastItem->item_number);
                    if (count($parts) === 3) {
                        $lastIncrement = (int)end($parts);
                        $increment = $lastIncrement + 1;
                    }
                }
                
                // Format the new item number
                $model->item_number = sprintf(
                    '%s-%d-%04d',
                    $shopInitials,
                    $shop->id,
                    $increment
                );
                
            } catch (\Exception $e) {
                // Log error if item number generation fails
                \Log::error('Error generating item number: ' . $e->getMessage());
                throw $e; // Re-throw the exception to prevent saving
            }
        });
    }

    public function shop()
    {
        return $this->belongsTo(ShopNames::class, 'shop_id');
    }

    public function completedRepair()
    {
        return $this->hasOne(CompleteShopRepair::class, 'repair_item_id');
    }
}