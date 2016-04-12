<?php
namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model  {

    use SoftDeletes;

    protected $table = "sites";

//    public function users()
//    {
//        return $this->belongsToMany('Cms\Models\User')->withTimestamps()->withPivot('user_level');
//    }

}
