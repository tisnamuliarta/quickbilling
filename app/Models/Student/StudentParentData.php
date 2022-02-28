<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentParentData extends Model
{
    protected $table = 'sch_students';

    protected $fillable = [
        'name_father',
        'nik_father',
        'name_mother',
        'nik_mother'
    ];
}
