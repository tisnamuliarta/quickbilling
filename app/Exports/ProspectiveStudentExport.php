<?php

namespace App\Exports;

use App\Exports\Sheets\ProspectiveStudentGeneralExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProspectiveStudentExport implements WithMultipleSheets
{
    use Exportable;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new ProspectiveStudentGeneralExport($this->request, 'general'),
            new ProspectiveStudentGeneralExport($this->request, 'score'),
            new ProspectiveStudentGeneralExport($this->request, 'parent'),
            new ProspectiveStudentGeneralExport($this->request, 'help'),
        ];
    }
}
