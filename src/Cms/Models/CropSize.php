<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class CropSize extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'cropsizes';
    protected $fillable = ['name', 'aspect_ratio', 'max_dimension'];
    protected $hidden = ['updated_at', 'created_at'];
}
