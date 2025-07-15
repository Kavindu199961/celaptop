<?php
// app/Models/Estimate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\EstimateCounter;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_number',
        'customer_name',
        'customer_phone',
        'sales_rep',
        'issue_date',
        'expiry_date',
        'total_amount',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(EstimateItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($estimate) {
            $userId = Auth::id();

            // Find or create counter for the user
            $counter = EstimateCounter::firstOrCreate(
                ['user_id' => $userId],
                ['last_number' => 0]
            );

            // Get shop details and create prefix
            $shop = MyShopDetail::where('user_id', $userId)->first();
            $prefix = $shop && $shop->shop_name 
                        ? strtoupper(substr(preg_replace('/\s+/', '', $shop->shop_name), 0, 2)) 
                        : 'XX';

            // Increment and save
            $counter->last_number += 1;
            $counter->save();

            // Set estimate number with shop prefix
            $estimate->estimate_number = $prefix . '-EST-' . str_pad($counter->last_number, 4, '0', STR_PAD_LEFT);
            $estimate->user_id = $userId;
        });
    }
}