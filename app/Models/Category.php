<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    public $timestamps = false;
    protected $fillable =  ['id', 'fid', 'cat_name', 'description', 'cat_image', 'sort', 'display', 'type', 'url', 'path', 'created_at'];
}
