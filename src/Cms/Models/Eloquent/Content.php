<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Content extends Model  {

    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'contents';

    protected $fillable = [
        'block_id',
        'type',
        'order',
        'resource_id',
        'data',
        'styling',
        'image_id',
        'template'
    ];

    protected $hidden = [
        'site_id',
        'updated_at',
        'created_at'
    ];
    
    public function resource()
    {
        return $this->hasOne('\Cms\Models\Block', 'id', 'resource_id');
    }

    public function image()
    {
        return $this->hasOne('\Cms\Models\Image', 'id', 'image_id');
    }

    public function field()
    {
        return $this->hasOne('\Cms\Models\FormField', 'id', 'resource_id');
    }
}
