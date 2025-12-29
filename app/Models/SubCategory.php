<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    Protected $table = 'sub_category_tbl';
    protected $fillable = ['category_id','sub_categoryname', 'sub_icon_image', 'sub_category_desc', 'sub_banner_image','status','sub_category_service','created_at','updated_at'];


     public function services()
    {
        return $this->hasMany(ServiceModel::class, 'subCategory_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
