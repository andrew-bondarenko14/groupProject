<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_reset';
    protected $fillable = ['email','reset_token','url_token'];
}
