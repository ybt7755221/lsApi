<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'email', 'password', 'remember_token',
    ];
    public $timestamps = true;
    protected $table = 'users';

    /**
     * Relation function for content table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function content()
    {
        return $this->hasMany('App\Models\content', 'user_id');
    }
}
