<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryModel extends Model
{
    use HasFactory;

        protected $table = 'tbl_enquiry';
       protected $fillable = [
            'order_no',
            'customer_id',
            'pickup_location',
            'pickup_lat',
            'pickup_lng',
            'drop_location',
            'drop_lat',
            'drop_lng',
            'flat_shop_no',
            'shipping_date_time',
            'floor_number',
            'pickup_services_lift',
            'drop_services_lift',
            'vehicle_number',
            'notes',
            'products_item',
            'total_km_cost',
            'total_amount',
            'km_distance',
            'rate_type',
            'km_rate',
            'km_profit',
            'destination_floor_number',
            'created_at',
            'updated_at',
        ];

public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

public function enquiryServices()
{
    return $this->hasMany(EnquiryserviceModel::class,  'id');
}

// App/Models/EnquiryserviceModel.php

public function subCategory()
{
    return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
}

public function payments()
{
    return $this->hasMany(Payment::class, 'enquiry_id', 'id');
}



protected static function boot()
{
    parent::boot();

    static::deleting(function ($enquiry) {
        $enquiry->payments()->delete();
    });
}
}
