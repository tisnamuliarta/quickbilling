<?php

namespace App\Models\Payroll;

use App\Models\Financial\PaymentMethod;
use App\Models\Master\Bank;
use IFRS\Models\Account;
use IFRS\Models\Entity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

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
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

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

    public function commission(): HasMany
    {
        return $this->hasMany(EmployeeCommission::class);
    }

    public function payMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    public function paySchedule(): BelongsTo
    {
        return $this->belongsTo(PaySchedule::class);
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
