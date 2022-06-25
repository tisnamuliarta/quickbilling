<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $table = 'sch_student_details';

    protected $fillable = [
        'father_born_place',
        'father_dob',
        'father_education',
        'father_job',
        'father_income',
        'father_special_need',
        'mother_born_place',
        'mother_dob',
        'mother_education',
        'mother_job',
        'mother_income',
        'mother_special_need',
        'guardian_parent_born_place',
        'guardian_parent_dob',
        'guardian_parent_education',
        'guardian_parent_job',
        'guardian_parent_income',
        'guardian_parent_special_need',
        'guardian_parent_name',
        'guardian_parent_nik',
        'is_parent_complete',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataFatherEducation()
    {
        return $this->hasOne(SchoolGrade::class, 'id', 'father_education');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataFatherJob()
    {
        return $this->hasOne(ParentJob::class, 'id', 'father_job');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataFatherIncome()
    {
        return $this->hasOne(Income::class, 'id', 'father_income');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataFatherSpecialNeeds()
    {
        return $this->hasOne(SpecialNeed::class, 'id', 'father_special_need');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataMotherEducation()
    {
        return $this->hasOne(SchoolGrade::class, 'id', 'mother_education');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataMotherJob()
    {
        return $this->hasOne(ParentJob::class, 'id', 'mother_job');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataMotherIncome()
    {
        return $this->hasOne(Income::class, 'id', 'mother_income');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataMotherSpecialNeeds()
    {
        return $this->hasOne(SpecialNeed::class, 'id', 'mother_special_need');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataGuardianParentEducation()
    {
        return $this->hasOne(SchoolGrade::class, 'id', 'guardian_parent_education');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataGuardianParentJob()
    {
        return $this->hasOne(ParentJob::class, 'id', 'guardian_parent_job');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataGuardianParentIncome()
    {
        return $this->hasOne(Income::class, 'id', 'guardian_parent_income');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function dataGuardianParentSpecialNeeds()
    {
        return $this->hasOne(SpecialNeed::class, 'id', 'guardian_parent_special_need');
    }
}
