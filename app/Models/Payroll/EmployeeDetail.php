<?php

namespace App\Models\Payroll;

use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'employee_pay_details';

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payType()
    {
        return $this->belongsTo(PayType::class);
    }
}
