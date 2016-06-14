<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;
    protected $fillable =  ['fid', 'cat_name', 'description', 'cat_image', 'sort', 'display', 'type', 'url', 'created_at'];
}
