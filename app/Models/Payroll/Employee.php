<?php

namespace App\Models\Payroll;

use App\Models\Master\Bank;
use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Employee extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function workLocation()
    {
        return $this->belongsTo(workLocation::class);
    }

    public function payDetails()
    {
        return $this->hasMany(EmployeeDetail::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
