<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Event;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\EventRepository;

/**
 * Class EventController
 * @package Cms\Http\Controllers
 */
class EventController extends ApiController
{
    /**
     * EventController constructor.
     * @param EventRepository $repo
     */
    public function __construct(EventRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Event
     */
    public function store(Request $request)
    {
        $event = $this->repo->newModel();
        $event->fill($request->all());
        $event->save();

        return $event;
    }


    /**
     * @param Request $request
     * @param $id
     * @return Event
     *
     */
    public function update(Request $request, $id)
    {
        $event = $this->repo->findById($id);
        $event->update($request->all());

        return $event;
    }


}
