<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Expertise extends Model
{

    protected $table = 'sch_expertise';

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }
}
