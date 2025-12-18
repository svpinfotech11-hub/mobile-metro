<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ServiceModel;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    public $incrementing = true; 
    protected $keyType = 'int';
    protected $fillable = ['product_id','service_id', 'product_subcat_id', 'sub_category_id', 'product_name','product_cft','status','created_at','updated_at'];
    

}
