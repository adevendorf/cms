<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Product extends Model
{
    
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'sku',
        'price',
        'discount',
        'inventory',
        'is_premium',
        'summary',
        'image_id',
        'status',
        'slug'
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
