<?php

namespace App\Models\Payroll;

use App\Models\Master\Bank;
use IFRS\Models\Account;
use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Employee extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'gender' => 'integer',
        'payment_method' => 'integer',
        'salary' => 'double',
        'per_hour_rate' => 'double',
        'hour_per_day' => 'double',
        'day_per_week' => 'double',
        'hire_date' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'full_name',
    ];

    /**
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * @return BelongsTo
     */
    public function workLocation(): BelongsTo
    {
        return $this->belongsTo(workLocation::class);
    }

    /**
     * @return HasMany
     */
    public function payDetails(): HasMany
    {
        return $this->hasMany(EmployeeDetail::class);
    }

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
