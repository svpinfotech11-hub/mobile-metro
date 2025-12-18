<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryserviceModel extends Model
{
    use HasFactory;
    protected $table = 'enquiry_service_tbl';
   protected $fillable = [
        'order_no',
        'customer_id',
        'service_description',
        'flat_no',
        'service_location',
        'service_name',
        'service_date',
        'pickup_location',
        'pickup_lat',
        'pickup_lng',
        'drop_location',
        'drop_lat',
        'drop_lng',
        'vehicle_number',
        'notes',
        'shipping_date_time',
        'km_distance',
        'rate_type',
        'km_rate',
        'km_profit',
        'total_amount',
        'created_at',
        'updated_at',
        'id',
        'id',
        'product_id',
    ];
     public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(ServiceModel::class, 'service_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

      public function subCategory()
    {
        // Make sure `sub_category_id` exists in enquiry_service_tbl
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
