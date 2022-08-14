<?php

namespace App\Models\Payroll;

use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDetail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $guarded = [];

    protected $table = 'employee_pay_details';

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payType(): BelongsTo
    {
        return $this->belongsTo(PayType::class);
    }
}
