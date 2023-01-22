<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    use HasFactory;
    protected $table = 'user_auth';
    protected $fillable = [
        'id_user',
        'token',
        'data',
        'expired',
        'created_at',
        'updated_at',
    ];

    public function getUser()
    {
        return $this->belongsTo(Users::class,'id_user','id');
    }

}
