<?php


namespace App\Exports\Sheets;

use App\Models\Student\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProspectiveStudentGeneralExport implements FromView, WithTitle, ShouldAutoSize
{
    private $status;
    private $search_year;

    /**
     * ProspectiveStudentGeneralExport constructor.
     * @param $search_year
     * @param $status
     */
    public function __construct($search_year, $status)
    {
        $this->status = $status;
        $this->search_year = $search_year;
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        $search_year = $this->search_year;
//        foreach ($this->dataStudent($search_year) as $itm) {
//            dd($itm->data_parent->data_father_education);
//            dd((isset($itm->data_parent)) ? $itm->data_parent->father_education->name : '');
//        }
        switch ($this->status) {
            case 'general':
                return view('export.prospective_student_general', [
                    'title' => 'DATA CALON SISWA',
                    'datum' => $this->dataStudent($search_year),
                ]);
            case 'score':
                return view('export.prospective_student_score', [
                    'title' => 'DATA NILAI',
                    'datum' => $this->dataStudent($search_year),
                ]);
            case 'parent':
                return view('export.prospective_student_parent', [
                    'title' => 'DATA ORANG TUA',
                    'datum' => $this->dataStudent($search_year),
                ]);
            case 'help':
                return view('export.prospective_student_data_help', [
                    'title' => 'DATA BANTUAN',
                    'datum' => $this->dataStudent($search_year),
                ]);
        }
    }

    /**
     * @param $request
     * @param $select
     * @return mixed
     */
    public function dataStudent($search_year)
    {
        $students = Student::leftJoin("ppdb as B", "B.id", "students.ppdb_id")
            ->select("students.*")
            ->where("B.start_year", "=", $search_year)
            //->whereBetween("created_at", [$request->startDate, $request->endDate])
            //->where("approval_step", "LIKE", "%$report_type%")
            ->distinct()
            ->get();
        return $students;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->status;
    }
}
