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
        'customer_number'
    ];

    protected $casts = [
        'date' => 'date',
        'repair_price' => 'decimal:2',
    ];

    /**
     * Boot method to auto-generate customer_number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->customer_number)) {
                $model->customer_number = static::generateCustomerNumber();
            }
        });
    }

    /**
     * Generate unique customer number in format CE-0001
     */
    public static function generateCustomerNumber()
    {
        $newNumber = Counter::incrementAndGet('customer_number');
        return 'CE-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate note number using NoteCounter
     */

/**
 * Generate note number using NoteCounter
 */
public static function generateNoteNumber()
{
    return NoteCounter::getNextNoteNumber();
}

/**
 * Get current note number without incrementing
 */
public static function getCurrentNoteNumber()
{
    return NoteCounter::getCurrentNoteNumber();
}  /**
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
}