<?php

namespace App\Traits;

use App\Models\Student\Expertise;
use App\Models\Student\Major;
use App\Models\Student\StudentRegistration;
use App\Models\Student\School;
use Illuminate\Support\Facades\DB;

trait StudentHelper
{
    /**
     * @return mixed
     */
    protected function school()
    {
        return School::first();
    }

    /**
     * @return Major[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function majors()
    {
        return Major::all();
    }

    /**
     * @return Expertise[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function expertiseAll()
    {
        $expertise = Expertise::all();
        $arr_data = [];
        foreach ($expertise as $item) {
            $arr_data [$item->major_id] = [
                $item->id, $item->name
            ];
        }
        return $arr_data;
    }

    /**
     * @param $major
     * @return mixed
     */
    protected function expertise($major)
    {
        return Expertise::where("major_id", "=", $major)->get();
    }

    /**
     * @return mixed
     */
    protected function checkOpenRegistration()
    {
        return StudentRegistration::where("start_year", "=", date("Y"))
            ->first();
    }

    /**
     * @return mixed
     */
    protected function ppdbThisYear()
    {
        $year = date('Y');
        return StudentRegistration::where('start_year', "=", $year)->first();
    }

    /**
     * @param string $table
     * @param string $term
     * @param string $field
     * @param int $length
     * @param $ppdb
     * @return string
     */
    protected function generateCode(string $table, string $term, string $field, int $length, $ppdb): string
    {
        $term_length = strlen($term);
        $unique_table = DB::table($table)
            ->where("registration_id", "=", $ppdb->id)
            ->orderBy("created_at", "DESC")
            ->first();

        $selected_field = (!$unique_table) ? $term . sprintf("%0$length" . "s", 0) : $unique_table->$field;
        $actual_number = (int)substr($selected_field, $term_length, ($term_length + $length));
        $increase_number = $actual_number + 1;
        //dd($actual_number);
        //dd($term . sprintf("%0$length" . "s", $increase_number));
        return $term . sprintf("%0$length" . "s", $increase_number);
    }
}
