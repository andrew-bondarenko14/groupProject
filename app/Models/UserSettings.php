<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $attributes = [
        'backup_password' => '',
        'backup_email' => '',
        'codeword' => '',
        'security_notifications' => 0,
        'password_changed_at' => null,
        'two_step_authentication' => 0
    ];


    public function getHumanDate($value)
    {
        Carbon::setLocale('ru');
        return $value->format('d.m.Y H:i:s');
    }
    protected $guarded = [];
}
