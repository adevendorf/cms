<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class PageBlocks extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $table = 'page_blocks';
}
