<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Managers\TemplateManager;

/**
 * Class TemplateController
 * @package Cms\Http\Controllers\Cms\Api
 */
class TemplateController extends ApiController
{
    /**
     * @var TemplateManager
     */
    protected $templateManager;

    /**
     * TemplateController constructor.
     * @param TemplateManager $templateManager
     */
    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * @return array
     */
    public function index()
    {
        return $this->templateManager->get();
    }
}