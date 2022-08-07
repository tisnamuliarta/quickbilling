<?php

namespace App\Models\Payroll;

use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
