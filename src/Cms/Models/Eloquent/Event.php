<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Event extends Model  {
    
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'events';
    protected $fillable = [
        'title',
        'start',
        'end',
        'type',
        'details_id',
        'allday'
    ];
    protected $hidden = [
        'site_id',
        'updated_at',
        'created_at'
    ];
    protected $casts = [
        'allday' => 'boolean',
    ];

}
