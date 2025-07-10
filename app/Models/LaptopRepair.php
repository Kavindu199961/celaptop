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
        'images',
        'user_id',
        'email',
        'ram',
        'hdd',
        'ssd',
        'nvme',
        'battery',
        'dvd_rom',
        'keyboard',
    ];

    protected $casts = [
        'date' => 'date',
        'repair_price' => 'decimal:2',
        'hdd' => 'boolean',
        'ssd' => 'boolean',
        'nvme' => 'boolean',
        'battery' => 'boolean',
        'dvd_rom' => 'boolean',
        'keyboard' => 'boolean',
    ];

    public function scopeByStatus(Builder $query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}