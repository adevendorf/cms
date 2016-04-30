<?php
namespace Cms\Http\Controllers\Cms\Api;

use Cms\Http\Controllers\Cms\Base\ApiController;
use Illuminate\Http\Request;

class FormExportController extends ApiController
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var
     */
    protected $exporter;

    /**
     * FormExportController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->request = $request;


    }

    /**
     * @param $formId
     * @param $type
     */
    public function export($formId, $type)
    {
        $this->exporter = $this->setExporterType($type);
        $this->exporter->create($formId);
    }

    /**
     * @param $type
     * @return mixed
     */
    protected function setExporterType($type)
    {
        $exportType = '\\Cms\\Etc\\Helpers\\Exporter' . ucfirst($type);
        return new $exportType;
    }

}