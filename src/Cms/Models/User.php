<?php

namespace Cms\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'image_id', 'name', 'email', 'password',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'password', 'remember_token',
    ];

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }

//    public function sites()
//    {
//        return $this->belongsToMany('Cms\Models\Site')->withTimestamps()->withPivot('user_level');
//    }


    public function isAdmin()
    {
        return $this->user_level == 'ROLE_ADMIN';
    }

    public function isSuperAdmin()
    {
        return $this->user_level == 'ROLE_SUPER_ADMIN';
    }
}


