<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class ImageData extends Model
{

    use BelongsToTenant;
    use SoftDeletes;

    protected $table = "images";
    protected $fillable = [
        'parent_type',
        'parent_id',
        'asset_id',
        'caption',
        'created_by'
    ];

    public function asset()
    {
        return $this->hasOne('\Cms\Models\Asset', 'id', 'asset_id');
    }

    public function crops()
    {
        return $this->hasMany('\Cms\Models\Crop', 'id', 'image_id');
    }
}
