<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Event;
use Config;
use Auth;

/**
 * Class EventRepository
 * @package Cms\Repository
 */
class EventRepository extends Repository
{
    /**
     * @return Event
     */
    public function newModel()
    {
        $item = new Event;
        $item->created_by = $this->getUserId();
        return $item;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return Event::get();
    }

    public function getWithParams($request)
    {
        $items = Event::get();

        return $items;
    }

    /**
     * @param $request
     * @return Collection
     */
    public function paginate($request)
    {
        $items = Event::orderBy('id', 'asc');

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    /**
     * @param $id
     * @return Event
     * @throws \Exception
     */
    public function findBy($column, $value)
    {
        return Event::where($column, $value)
            ->firstOrFail();
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id)
    {
        $item = Event::findOrFail($id);
        $item->delete();
        return true;
    }
}