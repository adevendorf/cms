<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Category extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'group'];
    protected $hidden = ['updated_at', 'created_at'];
}
