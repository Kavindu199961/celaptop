<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
        'smtp_host',
        'smtp_port',
        'smtp_encryption',
        'email_username',
        'email_password', // This will be encrypted
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'permissions' => 'array',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function emailSetting()
{
    return $this->hasOne(UserEmailSetting::class);
}

// In your User model
public function myShopDetails()
{
    return $this->hasOne(MyShopDetail::class);
}

public function getPermissionsAttribute($value)
{
    return json_decode($value, true) ?? [];
}

public function setPermissionsAttribute($value)
{
    $this->attributes['permissions'] = json_encode($value);
}

public function hasPermission($permission)
{
    return in_array($permission, $this->permissions ?? []);
}

}
