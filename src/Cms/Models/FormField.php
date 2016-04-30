<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class FormField extends Model  {
    use BelongsToTenant;
    use SoftDeletes;


    protected $table = 'form_fields';
    protected $fillable = [
        'question',
        'question_type',
        'options',
        'required',
        'validation_msg',
        'created_by',
        'order',
        'encrypt'
    ];
    protected $hidden = ['updated_at', 'created_at'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            $item->addToIndex();
        });
        
        static::updated(function ($item) {
            $item->reindex();
        });

        static::deleted(function ($item) {
            $item->removeFromIndex();
        });
    }
}
