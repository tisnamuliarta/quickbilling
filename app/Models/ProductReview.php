<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'product_review_id';
    protected $table = 'product_reviews';
}
