<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\FormSubmission;
use Cms\Models\FormData;
use Cms\Models\Form;
use Cms\Models\FormField;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Cms\Repository\FormSubmissionRepository;
use Illuminate\Http\Request;

class FormSubmissionController extends ApiController
{

    public function __construct(FormSubmissionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request, $id)
    {
        return  $this->repo->paginate($request, $id);
    }

    public function show(Request $request, $id, $subId)
    {
        $items = FormSubmission::where('id', $subId)
            ->where('form_id', $id)
            ->with(
                'formData',
                'formData.question'
            )
            ->firstOrFail();


//        $items = FormSubmission::searchByQuery([
//            'match' => [
//                'form_id' => $id
//            ]
//        ]);
        return $items;
    }


    public function getAnswer($answer)
    {
        if ($answer['question']['encrypt']) {
            return Crypt::decrypt($answer['answer_' . $answer['answer_type']]);
        }
        return $answer['answer_' . $answer['answer_type']];
    }


    public function destroy(Request $request, $id, $subid)
    {
        $data = FormData::where('form_submission_id', $subid)->delete();
        $submission = FormSubmission::where('id', $subid)->delete();
        return ['success' => true];

    }
}