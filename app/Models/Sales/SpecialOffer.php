<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'special_offer_id';
    protected $table = 'special_offer';
}
