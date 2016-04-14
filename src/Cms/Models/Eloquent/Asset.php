<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Asset extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'assets';
    protected $fillable = [
        'original_filename',
        'path',
        'caption',
        'folder',
        'keywords',
        'type',
        'cms_filename',
        'crop_preference'
    ];
    protected $hidden = [
        'site_id',
        'updated_at',
        'created_at'
    ];

    public function images()
    {
        return $this->belongsToMany('\Cms\Image');
    }
}
