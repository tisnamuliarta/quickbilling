<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'sch_majors';

    public function expertise()
    {
        return $this->hasMany(Expertise::class, 'major_id');
    }
}
