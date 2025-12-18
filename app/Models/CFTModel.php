<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CFTModel extends Model
{
    use HasFactory;
    protected $table = 'cft_rate_tbl';
    protected $fillable = ['from_cft','to_cft','cft_rate','cft_profit','rate_type','applicable_date','created_at','updated_at'];

     public function cft()
    {
        return $this->belongsTo(CFTModel::class);
    }

}
