<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table =  'tbl_customer';
    protected $fillable = [
        'customer_name',
        'mobile_no',
        'email',
        'password',
        'city',
        'state',
        'pincode',
        'is_verified',
        'is_registered'
    ];
    
     public function enquiries()
    {
        return $this->hasMany(EnquiryModel::class, 'customer_id');
    }
    public function serviceEnquiries()
    {
        return $this->hasMany(EnquiryserviceModel::class, 'customer_id');
    }
}
