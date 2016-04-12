<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Render\PageRender;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Auth;
use Crypt;
use Cms\Models\Form;
use Cms\Models\Page;

class SurveyController extends ApiController
{
    protected $request;
    protected $model;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->request = $request;
        $this->model = new Form;
    }

    public function getStepOne($formSubId)
    {
        return redirect($this->request->url() . '/1');
    }

    public function getStep($formSubId, $step)
    {
        $form = $this->model->where('site_id', $this->site->id)
            ->with(
                'blocks',
                'blocks.content',
                'blocks.content.field',
                'blocks.content.resource',
                'blocks.content.image',
                'blocks.content.image.asset',
                'blocks.content.image.crops',
                'redirectPage'
            )
            ->where('submission_uuid', '=', $formSubId)
            ->first();

        if (!$form || $step > count($form->blocks)) {
            abort(404, 'Survey Not Found');
        }

        dd('to be continued...');
    }


}