<?php

namespace App\Models\Documents;

use App\Models\Financial\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Document extends Model
{
    use HasFactory;
    use RevisionableTrait;

    protected $guarded = [];
    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionForceDeleteEnabled = true;

    /**
     * @param $value
     * @return string
     */
    public function getIssueAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taxDetails()
    {
        return $this->hasMany(DocumentItemTax::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }
}
