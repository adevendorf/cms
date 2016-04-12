<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Extension;
use Config;
class ExtensionRepository extends Repository
{
    public function newModel()
    {
        $item = new Extension;
        $item->created_by = $this->getUserId();
        return $item;
    }

    public function paginate($request)
    {
        $items = Extension::orderBy('id', 'asc');

        if ($request->input('type')) {
            $items = $items->whereType($request->input('type'));
        }
        if ($request->input('key')) {
            $items = $items->whereKey($request->input('key'));
        }

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    public function findKeyInType($key, $type)
    {
        return Extension::where('key', $key)
            ->where('type', $type)
            ->firstOrFail();
    }

    public function findBy($column, $value)
    {
        return Extension::orderBy('id', 'asc')
            ->where($column, $value)
            ->firstOrFail();
    }

    public function destroy($id)
    {
        $item = Extension::findOrFail($id);
        $item->delete();
        return true;
    }
}