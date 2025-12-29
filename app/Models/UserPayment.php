<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table = 'user_payments';

    protected $fillable = [
        'user_enquiry_id',
        'amount',
        'method',
        'status'
    ];

    public function enquiry()
    {
        return $this->belongsTo(UserEnquiry::class, 'user_enquiry_id');
    }
    
}
