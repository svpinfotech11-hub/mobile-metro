<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'full_name',
        'business_name',
        'address',
        'mobile_no',
        'email',
        'business_type',
        'business_description',
        'experience_years',
        'service_areas',
    ];
}
