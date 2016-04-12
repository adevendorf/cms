<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\Form;
use Config;

class FormRepository extends Repository
{
    public function newModel()
    {
        $content = new Form;
        $content->created_by = $this->getUserId();
        return $content;
    }

    public function paginate($request)
    {
        $items = Form::orderBy('id', 'asc');

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }

    public function findBy($column, $value)
    {
        return Form::with(
                'blocks',
                'blocks.content',
                'blocks.content.field',
                'blocks.content.resource',
                'blocks.content.image',
                'blocks.content.image.asset',
                'blocks.content.image.crops',
                'redirectPage'
            )
            ->where($column, $value)
            ->firstOrFail();
    }

    public function destroy($id)
    {
        $content = Form::findOrFail($id);
        $content->delete();
        return true;
    }
}