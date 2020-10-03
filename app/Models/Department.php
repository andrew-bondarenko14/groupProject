<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'director_user_id'
    ];

    public function director()
    {
        return $this->belongsTo(User::class, 'director_user_id' );
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }


}
