<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ListSchool extends Model
{

    protected $table = 'sch_list_schools';

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
