<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletedRepair extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'completed_repairs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'contact',
        'date',
        'fault',
        'device',
        'repair_price',
        'serial_number',
        'note_number',
        'customer_number',
        'status',
        'completed_at',
        'images', // This will be a JSON field to store multiple image paths
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'completed_at' => 'datetime',
        'repair_price' => 'decimal:2'
    ];

    /**
     * Get the original repair record (if needed)
     */
    public function originalRepair()
    {
        return $this->belongsTo(LaptopRepair::class, 'customer_number', 'customer_number');
    }

    /**
     * Scope for filtering by completion date
     */
    public function scopeCompletedBetween($query, $from, $to)
    {
        return $query->whereBetween('completed_at', [$from, $to]);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}