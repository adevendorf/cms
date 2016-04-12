<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Contact extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'contacts';
    protected $fillable = ['email', 'first_name', 'last_name', 'active'];
    protected $hidden = [];

}
