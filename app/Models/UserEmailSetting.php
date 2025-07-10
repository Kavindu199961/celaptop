<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmailSetting extends Model
{
  protected $fillable = ['user_id', 'email', 'password', 'from_name'];

    protected $casts = [
    'password' => 'encrypted' // Laravel will automatically encrypt/decrypt
];
}
