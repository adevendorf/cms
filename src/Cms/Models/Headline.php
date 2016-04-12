<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Headline extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'headlines';
    protected $fillable = [
        'section_id',
        'page_id',
        'image_id',
        'headline',
        'redirect_url',
        'new_window',
        'active',
        'order'
    ];
    protected $hidden = [];

    public function images()
    {
        return $this->belongsToMany('\Cms\Image');
    }
}
