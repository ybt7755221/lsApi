<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'content';
    public $timestamps = true;
    protected $fillable = ['id', 'title', 'user_id', 'body', 'comment_status', 'state', 'cat_id'];
    protected $hidden = ['thumb', 'created_at', 'updated_at'];
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
