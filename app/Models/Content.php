<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'content';
    public $timestamps = true;
    protected $fillable = ['id', 'title', 'thumb', 'user_id', 'body', 'comment_status', 'state', 'cat_id'];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Relation function for the users table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {
        return $this->belongsTo('App\Models\User','user_id');
    }
    /**
     * Relation function for the category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category() {
        return $this->belongsTo('App\Models\category','cat_id');
    }
}
