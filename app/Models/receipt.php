<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class receipt extends Model
{
    protected $fillable = [
        'inv_num',
        'rec_amount',
        'rec_status',
        'cust_id',
    ]; 

      public function invoice()
    {
    return $this->belongsTo(Invoice::class, 'inv_num');
    }
}
