<?php
namespace Cms\Models;

use Cms\Models\Eloquent\Page as OrmModel;
use Cms\Traits\Render;
use Cms\Contracts\Renderable;
use Cache;

class Page extends OrmModel implements Renderable
{

    use Render;

    const CACHE_EXPIRE = 0.5;

    public function render($options = [])
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

        $page = $this;

        $pageBlocks = Cache::get('pageblocks:'.$page->id, function() use($page) {
            $value = $page->blocks;
            Cache::put('pageblocks:'.$page->id, $value, self::CACHE_EXPIRE);
            return $value;
        });

        foreach ($pageBlocks as $block) {
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
