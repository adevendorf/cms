<?php
namespace Cms\Repository;

use Cms\Repository\Base\Repository;
use Cms\Models\FormSubmission;
use Config;

class FormSubmissionRepository extends Repository
{
    public function paginate($request, $formId)
    {
        $items = FormSubmission::where('form_id', $formId)
            ->with(
                'formData',
                'formData.question'
            );

        $this->count = $request->input('count') ? $request->input('count') : $this->count;

        return $items->paginate($this->count);
    }
}