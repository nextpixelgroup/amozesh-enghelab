<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = "o_t_p_s";
    protected $fillable = ['login', 'code', 'attempts', 'expires_at'];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
