<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
     protected $fillable = [
        'prod_code',
        'prod_name',
        'prod_amount',
        'prod_desc'
    ];
}
