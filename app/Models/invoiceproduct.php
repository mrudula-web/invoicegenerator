<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invoiceproduct extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit',
        'price',
        'total_price',
    ];  
     public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    
}
