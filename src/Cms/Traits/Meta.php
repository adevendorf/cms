<?php
namespace Cms\Traits;

trait Meta
{

    /**
     * Render the meta html tags as a group
     *
     * @param $page
     * @return string
     */
    public function buildMetas($page)
    {
        $html = '';
        $html .= $this->buildGoogle($page);
        $html .= $this->buildKeywords($page);
        $html .= $this->buildDescription($page);
        $html .= $this->buildOGImage($page);

        return $html;
    }


    /**
     * Create the description tag
     *
     * @param $page
     * @return string
     */
    public function buildDescription($page)
    {
        if ($page->description == '') return '';
        return '<meta name="description" content="' . $page->description . '">' . "\n";
    }


    /**
     * Create the keywords tag
     *
     * @param $page
     * @return string
     */
    public function buildKeywords($page)
    {
        if ($page->keywords == '') return '';
        return '<meta name="keywords" content="' . $page->keywords . '">' . "\n";
    }


    /**
     * Create the OpenGraph Image tag
     *
     * @param $page
     * @return string
     */
    public function buildOGImage($page)
    {
        if ($page->ogimage == '') return '';
        return '<meta property="og:image" content="' . $page->ogimage . '">' . "\n";
    }


    /**
     * Create the GoogleBot tag
     *
     * @param $page
     * @return string
     */
    public function buildGoogle($page)
    {
        $html = '';

        if ($page->goog_no_index == 1 || $page->goog_index == 1 || $page->goog_no_follow == 1 || $page->goog_follow == 1) {
            $html = '<meta name="robots" content="';
            if ($page->goog_no_index == 1) $html .= "noindex";
            if ($page->goog_index == 1) $html .= "index";
            if ($page->goog_index == 1 || $page->goog_no_index == 1) $html .= ", ";
            if ($page->goog_no_follow == 1) $html .= "nofollow";
            if ($page->goog_follow == 1) $html .= "follow";
            $html .= '">' . "\n";
        }

        return $html;
    }
}