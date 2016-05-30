<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'content';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
