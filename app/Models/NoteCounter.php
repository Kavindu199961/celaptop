<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NoteCounter extends Model
{
    protected $fillable = ['key', 'value'];

    public static function incrementAndGet($key)
    {
        return DB::transaction(function () use ($key) {
            $counter = self::lockForUpdate()->firstOrCreate(['key' => $key], ['value' => 1]);
            $counter->value += 1;
            $counter->save();
            return $counter->value;
        });
    }

    
}
