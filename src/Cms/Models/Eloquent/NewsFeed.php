<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class NewsFeed extends Model  {
    
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'newsfeed';
    protected $fillable = [
        'section_id',
        'page_id',
        'custom_image',
        'custom_url',
        'custom_headline',
        'custom_synopsis',
        'image_id',
        'synopsis',
        'headline',
        'redirect_url',
        'new_window',
        'active',
        'order'
    ];
    protected $hidden = [
        'site_id',
        'updated_at',
        'created_at'
    ];

    public function page()
    {
        return $this->hasOne('\Cms\Models\Page', 'id', 'page_id');
    }

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }
}
