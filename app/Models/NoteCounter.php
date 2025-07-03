<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoteCounter extends Model
{
    protected $fillable = ['user_id', 'key', 'value'];

    public static function incrementAndGet($key)
    {
        $userId = Auth::id(); // Get currently logged-in user's ID

        return DB::transaction(function () use ($key, $userId) {
            $counter = self::lockForUpdate()->firstOrCreate(
                ['user_id' => $userId, 'key' => $key],
                ['value' => 1]
            );

            $counter->value += 1;
            $counter->save();

            return $counter->value;
        });
    }
}

