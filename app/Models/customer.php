<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'address',
        'phone_no_one',
        'other_details',
    ]; 
}
