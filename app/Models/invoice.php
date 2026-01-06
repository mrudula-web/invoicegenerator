<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    protected $fillable = [
        'inv_no',
        'inv_subtotal',
        'inv_tax',
        'inv_dis',
        'inv_total'
    ];
}
