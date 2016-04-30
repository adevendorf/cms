<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Content;
use Cms\Models\FormField;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Auth;
use Config;

class FormFieldController extends ApiController
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $items = FormField::paginate(50);
        return $items;
    }

    /**
     * @param Request $request
     * @return FormField
     */
    public function store(Request $request)
    {
        $item = new FormField;
        $item->fill($request->all());
        $item->created_by = $request->user()->id;
        $item->save();

        $content = Content::findOrFail($request->input('parent_id'));
        $content->resource_id = $item->id;
        $content->save();


        return $item;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $item = FormField::findOrFail($id);
        return $item;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
//        $this->validate($request, [
//            'name' => 'required'
//        ]);

        $item = FormField::findOrFail($id);
        $item->update($request->all());

        $item->reindex();

        return $item;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $item = FormField::findOrFail($id);
        $item->delete();
        return response()->json(null, 200);
    }
}
