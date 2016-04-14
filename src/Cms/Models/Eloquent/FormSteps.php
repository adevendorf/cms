<?php
namespace Cms\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class FormBlocks extends Model  {
    use BelongsToTenant;
    use SoftDeletes;


    protected $table = 'blocks';
    protected $fillable = ['type', 'title', 'slug', 'data', 'status', 'page_id', 'styling', 'created_by'];
    protected $hidden = ['updated_at', 'created_at'];

    public function fields()
    {
        return $this->hasMany('\Cms\Models\FormContent', 'form_steps_id', 'id')->orderBy('order', 'asc');
    }

    protected static function boot() {
        parent::boot();

        static::created(function ($item) {
            $item->addToIndex();
        });
        static::updated(function ($item) {
            $item->reindex();
        });
        static::deleted(function($item) {
            $item->removeFromIndex();
        });
    }
}
