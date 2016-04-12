<?php

namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\PageRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;


/**
 * Class DashboardController
 * @package Cms\Http\Controllers
 */
class DashboardController extends ApiController
{

    protected $pageRepo;

    public function __construct(PageRepository $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function index(Request $request)
    {
        $lastEdited = $this->pageRepo->findLastEdited();
        $upcomingPublishes = $this->pageRepo->findUpcomingPublishing();

        return [
            'last_edited' => $lastEdited,
            'scheduled_publishing' => $upcomingPublishes
        ];
    }

}
