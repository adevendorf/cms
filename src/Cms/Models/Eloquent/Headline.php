<?php
namespace Cms\Models\Eloquent;

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
        'order',
    ];
    protected $hidden = [
        'site_id',
    ];

    public function images()
    {
        return $this->belongsToMany('\Cms\Image');
    }
}
