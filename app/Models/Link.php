<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'link';
    public $timestamps = false;
    protected $fillable = ['id', 'url', 'thumb', 'title', 'thumb', 'description', 'status'];
    protected $hidden = ['created_at'];
}
