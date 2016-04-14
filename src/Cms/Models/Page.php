<?php

namespace Cms\Models;

use Cms\Models\Eloquent\Page as EloquentModel;

class Page extends EloquentModel  {

    public function publish()
    {
        $this->status = 'published';
        $this->scheduled_publish = null;
        $this->save();

        return $this;
    }

    public function hide()
    {
        $this->status = 'hidden';
        $this->scheduled_unpublish = null;
        $this->save();

        return $this;
    }

    public function fullpath()
    {
        if ($this->type == 'page') return '/' . $this->slug;

        $path = '/' . config('cms.blog_path') . '/' . $this->category->slug . '/' . $this->slug;
        return $path;
    }
}
