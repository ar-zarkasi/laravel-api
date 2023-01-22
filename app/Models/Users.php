<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\Uuid;

class Users extends Authenticatable
{
    use HasFactory, HasApiTokens, HasFactory, Notifiable, Uuid;
    
    protected $table = 'users';
    protected $primaryKey = 'id';
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
    
    public $regex_email = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
    public $regex_phone = '^(?:(?:\+|00) [1-9] [0-9]{0,2}|0)[1-9][0-9]{9}$';

    public function roles()
    {
        return $this->belongsTo(Roles::class,'id_roles','id');
    }
    public function token_table()
    {
        return $this->hasMany(UserAuth::class,'id_user','id');
    }
}
