<?php

namespace App\Models\Payroll;

use App\Models\User;
use IFRS\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Payroll extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'main_account_amount' => 'double',
    ];


    protected $guarded = [];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function lineItem(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
