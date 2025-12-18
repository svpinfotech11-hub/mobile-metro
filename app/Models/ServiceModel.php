<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_services';
    protected $fillable = ['category_id','subCategory_id','service_name','service_banner_image','service_icon_image','service_desc','status','created_at','updated_at'];

    public function products()
    {
        return $this->hasMany(ProductModel::class, 'service_id', 'id');
    }

}
