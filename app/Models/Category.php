<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
     protected $table = 'category_tbl';
      protected $fillable = ['name','category_desc', 'icon_image'];

       public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

}
