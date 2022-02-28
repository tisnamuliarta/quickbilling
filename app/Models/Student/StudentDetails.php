<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentDetails extends Model
{
    protected $table = 'sch_student_details';

    protected $fillable = [
        'no_bird_card',
        'religion_id',
        'special_need_id',
        'nationality',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'dusun_name',
        'rt_name',
        'rw_name',
        'zip_code',
        'residence_id',
        'transportation_id',
        'family_order',
        'sibling_number',
        'blood_group_id',
        'home_phone',
        'email',
        'extracurricular_id',
        'height',
        'head_circumference',
        'weight',
        'school_home_distance',
        'travel_time',
        'user_id',
        'is_details_complete',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function religion()
    {
        return $this->hasOne(Religion::class, 'id', 'religion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function spesialNeeds()
    {
        return $this->hasOne(SpecialNeed::class, 'id', 'special_need_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function regency()
    {
        return $this->hasOne(Regency::class, 'id', 'regency_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function village()
    {
        return $this->hasOne(Village::class, 'id', 'village_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function residents()
    {
        return $this->hasOne(Residence::class, 'id', 'residence_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transportation()
    {
        return $this->hasOne(Transportation::class, 'id', 'transportation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bloodGroup()
    {
        return $this->hasOne(BloodGroup::class, 'id', 'blood_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function extracurricular()
    {
        return $this->hasOne(Extracurricular::class, 'id', 'extracurricular_id');
    }
}
