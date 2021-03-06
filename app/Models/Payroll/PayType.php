<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employeeDetail()
    {
        return $this->hasMany(EmployeeDetail::class);
    }
}
