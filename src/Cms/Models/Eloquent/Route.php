<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Route extends Model  {
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'routes';
    protected $fillable = [
        'url',
        'page_id',
        'redirect',
        'redirect_to',
        'created_by',
        'modified_by'
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function page()
    {
        return $this->belongsTo('Cms\Models\Page');
    }
}
