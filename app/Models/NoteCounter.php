<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteCounter extends Model
{
    protected $fillable = ['key', 'value'];
    public $timestamps = true;

    public static function incrementAndGet($key)
    {
        $counter = static::firstOrCreate(['key' => $key], ['value' => 0]);
        $counter->increment('value');
        return $counter->value;
    }
}
