<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    protected $fillable = [
        'quote_no',
        'quoteinv_subtotal',
        'quoteinv_tax',
        'quoteinv_dis',
        'quoteinv_total',
        'quotecust_id',
        'quote_terms'
    ];
    public function quoteproducts()
    {
        return $this->hasMany(quoteproducts::class, 'quotation_id');
    }   
    public function customer()
    {
    return $this->belongsTo(Customer::class, 'quotecust_id');
    }
}
