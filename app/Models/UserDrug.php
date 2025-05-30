<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDrug extends Model
{
    protected $fillable = [
        'user_id',
        'ndc_code',
        'brand_name',
        'generic_name',
        'labeler_name',
        'product_type',
        'source',
    ];
}