<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditShop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact',
        'address',
        'user_id'
    ];

    /**
     * Get the user that owns the credit shop.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}