<?php

namespace App\Models;

use Razorpay\Api\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'tbl_payments';

    protected $fillable = [
        'enquiry_id',
        'order_no',
        'razorpay_order_id',
        'razorpay_payment_id',
        'amount', // adv payment
        'total_amount', //total am
        'currency',
        'payment_status',
        'payment_method',
        'qr_code_url',
        'webhook_payload',
        'customer_id',
        'payment_date',
        'remaining_amount' // bal to paye
    ];

    // protected $casts = [
    //     'webhook_payload' => 'array',
    //     'amount' => 'float',
    // ];

      protected $casts = [
        'amount' => 'float',
        'total_amount' => 'float',
        'remaining_amount' => 'float',
    ];

    // Optional: define relationship with enquiry
    public function enquiry()
    {
        return $this->belongsTo(EnquiryModel::class, 'enquiry_id', 'id');
    }

    /**
     * Get payment status badge text
     */
    public function getStatusTextAttribute()
    {
        return match ($this->payment_status) {
            'success' => 'Paid',
            'failed' => 'Failed',
            default => 'Pending',
        };
    }

    /**
     * Get payment success boolean
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'success';
    }

    // app/Models/Payment.php
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }

}
