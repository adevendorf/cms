<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Traits\CmsRepo;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\FormRepository;

class FormController extends ApiController
{
    /**
     * @var FormRepository
     */
    protected $repo;
    use CmsRepo;

    /**
     * FormController constructor.
     * @param FormRepository $repo
     */
    public function __construct(FormRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->repo->paginate($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
//        $item = $this->generator->createNewForm($request);
        return $item;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Cms\Traits\Exception
     */
    public function storeBlock(Request $request)
    {
        $form = $this->repo->findById($id);

        $block = $this->getRepository('block')->newModel();
        $block->fill($request->all());
        $block->type = 'form';

        $form->blocks()->save($block);

        return $block;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(request $request, $id)
    {
        return $this->repo->findById($id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $item = $this->repo->findById($id);
        $item->update($request->all());

        return $item;
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $this->repo->destroy($id);
        return response()->json(null, 200);
    }
}
