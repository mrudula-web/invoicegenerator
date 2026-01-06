<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
     protected $fillable = [
        'cmpny_name',
        'cmpny_email',
        'cmpny_phone',
        'cmpny_address',
        'cmpny_other',
        'tax_name',
        'tax_perc',
        'set_currency'
    ]; 
}
