<?php
namespace Cms\Managers;

use Illuminate\Http\Request;
use Cms\Traits\CmsRepo;

class PageManager 
{
    use CmsRepo;

    public function create(Request $request)
    {
        $extension = $this->getRepository('extension');
        $defaultSectionId = $extension->findKeyInType('section', 'default');

        $page = $this->getRepository('page')->newModel();
        $page->fill($request->all());
        $page->title = "New";
        $page->section_id = $defaultSectionId->value;
        $page->slug = "new-blank-page";
        $page->save();

        $block = $this->getRepository('block')->newModel();
        $block->title = 'main';
        $block->type = 'page';

        $page->blocks()->save($block);

        return $page;
    }
}