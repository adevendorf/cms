<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;
use Cache;

class Page extends Model  {

    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'pages';
    protected $fillable = [
        'published_at',
        'author_id',
        'category_id',
        'section_id',
        'title',
        'keywords',
        'description',
        'synopsis',
        'status',
        'allow_comments',
        'type',
        'allow_headline',
        'section_id',
        'goog_no_index',
        'goog_index',
        'goog_no_follow',
        'goog_follow',
        'template_name',
        'access_groups',
        'require_https',
        'ignore_in_search',
        'ignore_in_sitemap',
        'scheduled_publish',
        'scheduled_unpublish',
        'created_by',
        'created_at',
        'updated_at',
        'modified_by',
        'image_id',
        'original_id'
    ];

    protected $hidden = ['deleted_at', 'site_id'];

    public function route()
    {
        return $this->hasOne('Cms\Models\Route');
    }

    public function author()
    {
        return $this->hasOne('\Cms\Models\User', 'id', 'author_id');
    }

    public function category()
    {
        return $this->belongsTo('\Cms\Models\Category', 'category_id', 'id');
    }

    public function blocks()
    {
        return $this->belongsToMany('\Cms\Models\Block', 'page_blocks');
    }

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }

    public function section()
    {
        return $this->belongsTo('\Cms\Models\Section', 'section_id', 'id');
    }
}
