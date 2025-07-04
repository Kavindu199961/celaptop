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
     * Generate unique customer number in 
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
