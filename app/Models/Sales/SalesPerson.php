<?php

namespace App\Models\Sales;

use App\Models\Payroll\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPerson extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'sales_persons';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
}
