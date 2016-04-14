<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Section extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'sections';
    protected $fillable = [
        'image_id',
        'name',
        'path'
    ];
    protected $hidden = [
        'site_id',
        'deleted_at'
    ];

    public function setPathAttribute($value)
    {
        $tmp = str_replace('/', 'escapedslashescapedslash', $value);
        $tmp = str_slug($tmp, '-');
        $tmp = str_replace('escapedslashescapedslash', '/', $tmp);
        if (substr($tmp, 0, 1) !== '/') $tmp = '/'.$tmp;
        if (substr($tmp, -1) !== '/') $tmp = $tmp.'/';
        $this->attributes['path'] = $tmp;
    }

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }
}
