<?php
namespace Cms\Models\Eloquent;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'image_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
    ];

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }

}


