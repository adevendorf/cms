<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Content;
use Config;

class ContentRepository extends Repository
{
    protected $contentType;

    public function setType($contentType)
    {
        $this->contentType = $contentType;
    }

    public function newModel()
    {
        $content = new Content;
        $content->created_by = $this->getUserId();
        return $content;
    }

    public function findBy($column, $value)
    {
        return Content::with(
            'image',
            'image.crops',
            'image.asset'
        )
        ->where($column, $value)
        ->firstOrFail();
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $content->delete();
        return true;
    }

    public function updateOrder($id, $order)
    {
        Content::where('id', $id)
            ->update([
                'order' => intval($order)
            ]);
        return true;
    }

    public function paginate($request)
    {
        $items = Content::with('image', 'image.crops', 'image.asset');

        foreach ($request->all() as $key => $val) {
            if ($key != 'count') {
                $items = $items->where($key, $val);
            }
        }

        if ($request->input('count')) {
            $this->count = $request->input('count') ? $request->input('count') : $this->count;
            return $items->paginate($this->count);
        }
        return $items->get();
    }
}