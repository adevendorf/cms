<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Models\Form;
use Illuminate\Http\Request;
use Cms\Http\Controllers\Cms\Base\ApiController;
use Auth;
use Faker\Factory;
use Cms\Models\FormField;
use Cms\Models\Block;
use Cms\Models\FormSubmission;
use Cms\Models\FormData;
use Crypt;
use Config;
use Mail;

class SubmitController extends ApiController
{

    public function getToken()
    {
        return [
            'token' => csrf_token()
        ];
    }

    public function submit(Request $request, $id)
    {
        $form = Form::with(
            'blocks',
            'blocks.content',
            'blocks.content.field',
            'blocks.content.resource',
            'blocks.content.image',
            'blocks.content.image.asset',
            'blocks.content.image.crops',
            'redirectPage'
        )
        ->where('submission_uuid', '=', $id)
        ->first();

        if (!$form) {
            abort(404, 'Page Not Found');
        }

        if ($form->action_email) {
            Mail::send('cms.emails.thankyou', ['id' => $form->id], function ($m) {
                $m->from(config('cms.contact_email'), config('cms.contact_email'));
                $m->to('andy@typeblue.com', 'user name')->subject('Thank you!');
            });
        }


        if ($form->save_data && $form->save_to == 'database') {
            $submission = new FormSubmission();

            if (Auth::user()) {
                $submission->user_id = Auth::user()->id;
            }

            $submission->user_ip = $request->getClientIp();
            $submission->form_id = $form->id;
            $submission->save();

            foreach ($request->input('form') as $key => $value) {
//                $questionId = intval(explode('_', $key)[1]);
                $questionId = intval($key);

                $answerType = $this->lookupQuestionType($questionId);

                $data = new FormData();
                $data->form_submission_id = $submission->id;
                $data->question_id = $questionId;
                $data->answer_type = $answerType;

                $subValue = $value;

                if ($this->shouldEncrypt($form->id, $questionId)) {
                    $subValue = Crypt::encrypt($subValue);
                }

                if ($answerType == 'string') {
                    $data->answer_string = $subValue;
                }

                if ($answerType == 'int') {
                    $data->answer_int = $subValue;
                }

                if ($answerType == 'text') {
                    $data->answer_text = $subValue;
                }

                $data->save();
            }
        }

        // update Form Counts
        $form->completed = $form->completed + 1;
        $form->save();


        if (count($form->blocks) == 1) {
            if (!$form->ajax) {
                if ($form->redirect_to_page == 1) {
                    return redirect($form->redirectPage->slug);
                }
                return redirect($form->redirect_url);
            }
        }

        return ['success' => true];

    }

    public function lookupQuestionType($id)
    {
        //TODO CACHE THIS
        $question = FormField::where('id', $id)->first();

        if ($question->question_type == 'multiline') {
            return 'text';
        }

        return 'string';

    }

    public function shouldEncrypt($formId, $questionId)
    {
        //TODO CACHE THIS
        $question = FormField::where('id', '=', $questionId)
            ->first();
        return $question->encrypt;

    }
}