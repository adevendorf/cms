<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Extension extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'extensions';
    protected $fillable = ['type', 'key', 'value'];
    protected $hidden = ['created_by', 'modified_by', 'deleted_at', 'updated_at', 'created_at'];
}
