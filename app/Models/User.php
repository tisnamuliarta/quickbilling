<?php

namespace App\Models;

use App\Models\Settings\Company;
use IFRS\Interfaces\Recyclable;
use IFRS\Traits\IFRSUser;
use IFRS\Traits\ModelTablePrefix;
use IFRS\Traits\Recycling;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Recyclable, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use IFRSUser;
    use Recycling;
    use ModelTablePrefix;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return MorphToMany
     */
    public function companies(): MorphToMany
    {
        return $this->morphToMany(Company::class, 'user', 'user_companies', 'user_id', 'company_id');
    }
}
