<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Crop extends Model  {

    use BelongsToTenant;
    use SoftDeletes;

    protected $table = "image_crops";
    protected $fillable = [
        'image_id',
        'name',
        'crop_x',
        'crop_y',
        'crop_width',
        'crop_height',
        'max_dimension',
        'aspect_ratio',
        'created_by'
    ];

    public function image()
    {
        return $this->belongsTo('\Cms\Models\Image');
    }
}
