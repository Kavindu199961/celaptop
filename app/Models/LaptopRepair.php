<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LaptopRepair extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'contact',
        'date',
        'fault',
        'device',
        'repair_price',
        'serial_number',
        'note_number',
        'status',
        'customer_number',
        'images', // This will be a JSON field to store multiple image paths
        'user_id', // Foreign key to the users table
    ];

    protected $casts = [
        'date' => 'date',
        'repair_price' => 'decimal:2',
    ];

    /**
     * Boot method to auto-generate customer_number and note_number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->customer_number)) {
                $model->customer_number = static::generateCustomerNumber();
            }

            if (empty($model->note_number)) {
                $model->note_number = static::generateNoteNumber();
            }
        });
    }

    /**
     * Generate unique customer number in format CE-0001
     */
  public static function generateCustomerNumber()
{
    $userId = auth()->id();

    // Fetch shop details for the current user
    $shop = MyShopDetail::where('user_id', $userId)->first();

    // Get first 2 letters of shop name as prefix, fallback to 'XX'
    $prefix = $shop && $shop->shop_name 
                ? strtoupper(substr(preg_replace('/\s+/', '', $shop->shop_name), 0, 2)) 
                : 'XX';

    // Get unique counter for this user
    $newNumber = Counter::incrementAndGet('customer_number_user_' . $userId);

    // Format: [SHOPPREFIX]-[USERID]-[SEQUENCE]
    return $prefix . '-' . $userId . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
}



    /**
     * Generate unique note number starting from 425
     */
  

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus(Builder $query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
