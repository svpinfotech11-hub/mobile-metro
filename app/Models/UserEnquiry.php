<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEnquiry extends Model
{
    use HasFactory;
    protected $fillable = [
    'category_id',
    'sub_category_id',
    'service_id',
    'service_name',
    'service_date',
    'pickup_location',
    'service_location',
    'drop_location',
    'floor_number',
    'lift_available',
    'vehicle_number',
    'service_description',
    'product_subcategory_id',
    'service_ids',
    'product_subcategory_ids',
    'user_id',


    // 🔽 calculated fields
    'km_distance',
    'km_rate',
    'km_cost',
    'km_profit',
    'km_rate_type',
    'total_amount',
    'products_item',
];

    public function products()
    {
        return $this->belongsToMany(
            ProductModel::class,
            'user_enquiry_products',
            'user_enquiry_id',
            'product_id'
        );
    }

    protected $casts = [
        'products_item' => 'array',
         'service_ids' => 'array',
    'product_subcategory_ids' => 'array',
    'service_date' => 'datetime',
    ];


     public function latestPayment()
    {
        return $this->hasOne(UserPayment::class, 'user_enquiry_id')->latestOfMany();
    }

    // ✅ CATEGORY
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ✅ SUB CATEGORY
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // ✅ SERVICE
    public function service()
    {
        return $this->belongsTo(ServiceModel::class, 'service_id');
    }

    // ✅ PAYMENTS
    public function payments()
    {
        return $this->hasMany(UserPayment::class, 'user_enquiry_id');
    }

    public function services()
    {
        return $this->belongsToMany(
            ServiceModel::class,
            'enquiry_services',
            'user_enquiry_id', // this table FK
            'service_id'       // related table FK
        );
    }


    public function productSubcategories()
    {
        return $this->belongsToMany(
            ProductSubCategory::class,
            'enquiry_product_subcategories',
            'user_enquiry_id',
            'product_subcategory_id' // 👈 exact column name
        );
    }


}
