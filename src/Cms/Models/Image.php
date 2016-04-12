<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Image extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = "images";
    protected $fillable = ['parent_type', 'parent_id', 'asset_id', 'caption', 'created_by'];

//    public function page()
//    {
//        return $this->belongsTo('\Cms\Page');
//    }
//
//    public function content()
//    {
//        return $this->belongsTo('\Cms\Content');
//    }

    public function asset()
    {
        return $this->hasOne('\Cms\Models\Asset', 'id', 'asset_id');
    }

    public function crops()
    {
        return $this->hasMany('\Cms\Models\Crop');
    }

//    protected static function boot() {
//        parent::boot();
//
//        static::deleting(function($image) {
//            foreach($image->crops as $crop)
//            {
//                $crop->delete();
//            }
//        });
//    }
}
