<?php namespace Cms\Services;
//
//use CmsImage;
//
//class RenderService
//{
//
//    protected $page;
//    protected $content;
//
//    public function setPageContent($page, $content)
//    {
//        $this->page = $page;
//        $this->content = $content;
//    }
//
//    public function section($name)
//    {
//        if (! isset($this->content[$name])) return '';
//
//        return $this->content[$name];
//    }
//
//    public function sectionImageUrl($name, $crop)
//    {
//        $sections = $this->page->blocks;
//        $section = $sections->where('title', $name)->first();
//
//        if (!$section || !$section->image_id || !$section->image) return '';
//
//        return CmsImage::generateUrls($section->image, $crop);
//    }
//
//    public function sectionImageAsBackground($name, $crop)
//    {
//        return view('cms.themes.' . config('cms.theme') . '.partials.section-background', [
//            'url' => $this->sectionImageUrl($name, $crop)
//        ]);
//
//    }
//
//
//}