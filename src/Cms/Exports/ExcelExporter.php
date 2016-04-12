<?php
namespace Cms\Exports;

use Excel;

use Cms\Models\Form;
use Cms\Models\FormField;
use Cms\Models\FormData;
use Cms\Models\FormSubmission;

class ExcelExporter
{

    public function create($id)
    {
        $sheetArr = [];
        $header = [];


//        $formQuestions = FormQuestion::where('form_id', '=', $id)->orderBy('order', 'asc')->get();
        $form = FormSubmission::where('form_id', '=', $id)
            ->with(
                'formData',
                'formData.question'
            )
            ->paginate(100);

//        dd($form->toArray());

        $header[] = 'USER_ID';
        $header[] = 'USER_IP';
        $header[] = 'ACCEPTED';
        $header[] = 'NOTES';
        $header[] = 'CREATED_AT';



        $index = 0;
        foreach ($form as $row) {

            $data = [];

            $data[] = $row->user_id;
            $data[] = $row->user_ip;
            $data[] = $row->accepted;
            $data[] = $row->notes;
            $data[] = $row->created_at;

            foreach ($row->formData as $field) {
                if ($index == 0) {
                    $header[] = strtoupper($field->question->question);
                }
                array_push($data, $this->getAnswer($field));
            }

            if ($index == 0) {
                array_push($sheetArr, $header);
            }

            array_push($sheetArr, $data);
            $index++;
        }

        Excel::create('Filename', function($excel) use($sheetArr) {
            $excel->sheet('Export', function($sheet) use($sheetArr) {
                $sheet->fromArray($sheetArr, null, 'A1', false, false);
            });
        })->download('xls');
    }

    public function getAnswer($field)
    {
        $field = $field->toArray();
        return $field['answer_'. $field['answer_type']];
    }
}
