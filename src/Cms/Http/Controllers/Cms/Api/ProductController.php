<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Product;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Auth;

class ProductController extends ApiController
{
    protected $request;
    protected $model;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->request = $request;
        $this->model = new Product;
    }

    public function index()
    {
        $items = $this->model->where('site_id', $this->site->id)->paginate(50);
        return $items;
    }

    public function store()
    {
        $this->validate($this->request, [
            'name' => 'required'
        ]);

        $item = $this->model;
        $item->fill($this->request->all());
        $item->site_id = $this->site->id;
        $item->created_by = $this->request->user()->id;
        $item->save();
        return $item;
    }

    public function show($id)
    {
        $item = $this->model->where('site_id', $this->site->id)->findOrFail($id);
        return $item;
    }

    public function update($id)
    {
        $this->validate($this->request, [
            'name' => 'required'
        ]);

        $item = $this->model->where('site_id', $this->site->id)->findOrFail($id);
        $item->update($this->request->all());
        return $item;
    }

    public function destroy($id)
    {
        $item = $this->model->where('site_id', $this->site->id)->findOrFail($id);
        $item->delete();
        return response()->json(null, 200);
    }
}
