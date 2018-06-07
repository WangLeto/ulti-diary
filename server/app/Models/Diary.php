<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $table = 'diary';

    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user'
    ];
}
