<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class FormData extends Model  {
    use BelongsToTenant;
    use SoftDeletes;


    protected $table = 'form_data';
    protected $fillable = ['form_submission_id', 'question_id', 'answer_type', 'answer_string', 'answer_text', 'answer_int'];
    protected $hidden = ['updated_at', 'created_at'];


    public function question()
    {
        return $this->hasOne('Cms\Models\FormField', 'id', 'question_id');
    }

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
