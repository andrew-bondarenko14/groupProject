<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserSecurityLogs extends Model
{
    public function getHumanDate($value)
    {
         Carbon::setLocale('ru');
         return $value->format('d.m.Y H:i:s');
    }
}
