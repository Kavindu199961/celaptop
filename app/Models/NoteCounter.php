<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteCounter extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Increment counter and return new value
     */
    public static function incrementAndGet($key)
    {
        $counter = static::firstOrCreate(
            ['key' => $key], 
            ['value' => 0]
        );
        
        $counter->increment('value');
        $counter->refresh(); // Ensure we get the updated value
        return $counter->value;
    }

    /**
     * Get current counter value without incrementing
     */
    public static function getCurrentValue($key)
    {
        $counter = static::where('key', $key)->first();
        return $counter ? $counter->value : 0;
    }

    /**
     * Reset counter to specific value
     */
    public static function resetCounter($key, $value = 0)
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get next note number (specific method for note numbers)
     */
    public static function getNextNoteNumber()
    {
        return static::incrementAndGet('note_number');
    }

    /**
     * Get current note number without incrementing
     */
    public static function getCurrentNoteNumber()
    {
        return static::getCurrentValue('note_number');
    }

    /**
     * Get next value without incrementing (peek at what the next value would be)
     */
    public static function getNextValue($key)
    {
        $current = static::getCurrentValue($key);
        return $current + 1;
    }
}