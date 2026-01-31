<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;  

class invoice extends Model
{
    protected $fillable = [
        'cust_id',
        'quote_no',
        'status',
        'inv_no',
        'inv_subtotal',
        'inv_tax',
        'inv_dis',
        'inv_total',
        'terms',
    ];
    public function invoiceproducts()
    {
        return $this->hasMany(invoiceproduct::class, 'invoice_id');
    }   
    public function customer()
    {
    return $this->belongsTo(Customer::class, 'cust_id');
    }
    

}
