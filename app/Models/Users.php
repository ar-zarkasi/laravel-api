<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model
{
    use HasFactory, HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'uuid',
        'fullname',
        'password',
        'username',
        'email',
        'phone',
        'salt',
        'id_roles',
        'active',
    ];

    protected $hidden = [
        'password',
        'salt',
    ];
    
}
