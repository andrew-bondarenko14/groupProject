<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccesses extends Model
{
    protected $attributes = [
            'disk' => 1,
            'mail' => 1,
            'calendar' => 1,
            'photos' => 1,
            'contacts' => 1,
    ];
    protected $guarded = ['id'];
}
