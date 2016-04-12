<?php
namespace Cms\Http\Controllers\Cms;

use Cms\Http\Controllers\Cms\Base\CmsController;
use Cms\Managers\UserManager;
use Illuminate\Http\Request;

/**
 * Class AdminController
 * @package Cms\Http\Controllers\Cms
 */
class AdminController extends CmsController
{

    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('cms.layouts.admin', [
            'userLevel' => $this->userManager->getUserLevel(),
            'token' => csrf_token()
        ]);
    }
}
