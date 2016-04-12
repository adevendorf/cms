<?php

namespace Cms\Models;

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

//    public function setSlugAttribute($value)
//    {
//        $this->attributes['slug'] = str_slug($value, '-');
//    }

//    public function setFields($array)
//    {
//        $this->hidden = array_merge($this->hidden, $this->fillable);
//
//        foreach($array as $item) {
//            $index = array_search($item, $this->hidden);
//            array_splice($this->hidden, $index, 1);
//        }
//    }

    public function publish()
    {
        $this->status = 'published';
        $this->scheduled_publish = null;
        $this->save();

//        Cache::forget('slug::' . $this->slug);
        return $this;
    }

    public function hide()
    {
        $this->status = 'hidden';
        $this->scheduled_unpublish = null;
        $this->save();

//        Cache::forget('slug::' . $this->slug);
        return $this;
    }

    public function fullpath()
    {
        if ($this->type == 'page') return '/' . $this->slug;

        $path = '/' . config('cms.blog_path') . '/' . $this->category->slug . '/' . $this->slug;
        return $path;
    }

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

//    protected static function boot() {
//        parent::boot();
//
//        static::deleting(function($page) {
//            foreach($page->blocks as $block)
//            {
//                $block->delete();
//            }
//
//            $resource = $page->resource();
//            if ($resource) {
//                $resource->delete();
//            }
//
//        });
//    }
}
