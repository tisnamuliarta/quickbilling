<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPartners extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $primaryKey = 'bp_id';

    protected $table = 'business_partners';
}
