<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quoteproducts extends Model
{
    protected $fillable = [
        'quotation_id',
        'quoteprod_id',
        'quote_quantity',
        'quote_unit',
        'quote_price',
        'quotetotal_price',
    ];
     public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
