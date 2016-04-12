<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Traits\CmsRepo;
use Illuminate\Http\Request;
use Cms\Generators\FormGenerator;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\FormRepository;

class FormController extends ApiController
{
    protected $repo;
    use CmsRepo;

    public function __construct(FormRepository $repo)
    {
        $this->repo = $repo;
        $this->generator = new FormGenerator;
    }

    public function index(Request $request)
    {
        return $this->repo->paginate($request);
    }

    public function store(Request $request)
    {
        $item = $this->generator->createNewForm($request);
        return $item;
    }

    public function storeBlock(Request $request)
    {
        $form = $this->repo->findById($id);

        $block = $this->getRepository('block')->newModel();
        $block->fill($request->all());
        $block->type = 'form';

        $form->blocks()->save($block);

        return $block;
    }

    public function show(request $request, $id)
    {
        return $this->repo->findById($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $item = $this->repo->findById($id);
        $item->update($request->all());

        return $item;
    }

    public function destroy(Request $request, $id)
    {
        $this->repo->destroy($id);
        return response()->json(null, 200);
    }
}
