<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteCounter extends Model
{
    protected $fillable = ['user_id', 'last_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
