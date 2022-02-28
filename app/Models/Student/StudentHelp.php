<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentHelp extends Model
{
    protected $table = 'sch_student_details';

    protected $fillable = [
        'kks_no',
        'is_kps_receiver',
        'kps_no',
        'is_pip_worthy',
        'pip_worthy_reason',
        'pip_no',
        'pip_name',
        'is_kip_receiver',
        'is_kip_physical_receiver',
        'is_help_complete',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function worthyReason()
    {
        return $this->hasOne(KipWorthyReason::class, 'id', 'pip_worthy_reason');
    }
}
