<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Counter extends Model
{
    protected $fillable = ['key', 'value', 'user_id'];
    public $timestamps = true;

    public static function incrementAndGet($key)
    {
        $userId = Auth::id();
        $counter = static::firstOrCreate(
            ['key' => $key, 'user_id' => $userId],
            ['value' => 0]
        );
        $counter->increment('value');
        return $counter->value;
    }

    // Relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}