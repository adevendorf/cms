<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Block extends Model
{

    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'blocks';
    protected $fillable = [
        'type',
        'image_id',
        'title',
        'slug',
        'data',
        'status',
        'styling',
        'created_by'
    ];

    protected $hidden = [
        'site_id',
        'updated_at',
        'created_at'
    ];

    public function content()
    {
        return $this->hasMany('\Cms\Models\Content', 'block_id', 'id')->orderBy('order', 'asc');
    }

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }
}
