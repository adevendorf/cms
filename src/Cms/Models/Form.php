<?php

namespace Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenant;

class Form extends Model  {
    use BelongsToTenant;
    use SoftDeletes;



    protected $table = 'forms';
    protected $fillable = [
        'submission_uuid',
        'title',
        'created_by',
        'redirect_page_id',
        'redirect_url',
        'send_email',
        'redirect_to_page',
        'email_to',
        'submit_text',
        'save_data',
        'save_to',
        'save_to_name',
        'ajax'
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function fields()
    {
        return $this->hasMany('Cms\FormField', 'form_id', 'id')->orderBy('order', 'asc');
    }

    public function blocks()
    {
        return $this->belongsToMany('\Cms\Models\Block', 'form_blocks');
    }

    public function redirectPage()
    {
        return $this->belongsTo('\Cms\Models\Page', 'redirect_page_id');
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
