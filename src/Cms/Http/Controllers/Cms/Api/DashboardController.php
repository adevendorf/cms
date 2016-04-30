<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Repository\PageRepository;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;


class DashboardController extends ApiController
{

    /**
     * @var PageRepository
     */
    protected $pageRepo;

    /**
     * DashboardController constructor.
     * @param PageRepository $pageRepo
     */
    public function __construct(PageRepository $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    /**
     * @param Request $request
     * @return array
     */
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
