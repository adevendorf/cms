<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use HipsterJazzbo\Landlord\BelongsToTenant;

class FormSubmission extends Model  {
    use BelongsToTenant;
    use SoftDeletes;


    protected $table = 'form_submissions';
    protected $fillable = ['form_id', 'user_id', 'user_ip'];
    protected $hidden = [];

    public function formData()
    {
        return $this->hasMany('Cms\Models\FormData', 'form_submission_id', 'id');
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
