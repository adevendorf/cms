<?php

namespace Cms\Http\Controllers\Cms\Api;

use Cms\Http\Controllers\Cms\Base\ApiController;
use Illuminate\Http\Request;

class FormExportController extends ApiController
{
    protected $request;
    protected $exporter;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->request = $request;


    }

    public function export($formId, $type)
    {
        $this->exporter = $this->setExporterType($type);
        $this->exporter->create($formId);
    }

    protected function setExporterType($type)
    {
        $exportType = '\\Cms\\Etc\\Helpers\\Exporter' . ucfirst($type);
        return new $exportType;
    }

}