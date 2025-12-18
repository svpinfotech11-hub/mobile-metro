<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    use HasFactory;

     protected $table = 'tbl_product_subcategory';

    protected $fillable = [
        'service_id',
        'subcat_name',
        'status'
    ];

    public function service()
    {
        return $this->belongsTo(ServiceModel::class, 'service_id');
    }
}
