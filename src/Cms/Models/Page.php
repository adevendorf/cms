<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Page as EloquentModel;
use Cms\Traits\Render;

class Page extends EloquentModel  {

    use Render;

    public function render() 
    {

        if ($this->type == 'blog') {
            $template = 'cms.themes.' . config('cms.theme') . '.blog.blog_post';
        } else {
            if ($this->template_name == '') {
                $template = 'cms.themes.' . config('cms.theme') . '.page.default';
            } else {
                $template = 'cms.themes.' . config('cms.theme') . '.page.' . $this->template_name;
            }
        }

        $meta = $this->buildMetas($this);

        $blocks = [];

        foreach ($this->blocks as $block) {
//            $html = $renderer->render($block->content, $this);
            $blocks[$block->title] = $block->render();
        }

        return view($template, [
            'page' => $this,
            'content' => $blocks,
            'meta' => $meta
        ]);
    }
    
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
