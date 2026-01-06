<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class receipt extends Model
{
    protected $fillable = [
        'inv_num',
        'rec_amount',
        'rec_status',
    ]; 
}
