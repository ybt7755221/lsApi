<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fields extends Model
{
    protected $table = 'fields';
    public $timestamps = false;
    protected $fillable = ['id', 'label', 'key', 'params', 'publish', 'field_type', 'user_id'];
    protected $hidden = ['hits'];
    
}
