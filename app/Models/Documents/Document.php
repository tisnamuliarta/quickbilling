<?php

namespace App\Models\Documents;

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

    public function getIssueAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    public function taxDetails()
    {
        return $this->hasMany(DocumentItemTax::class);
    }
}
