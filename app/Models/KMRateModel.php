<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KMRateModel extends Model
{
    use HasFactory;
    protected $table = 'km_rate_tb';
    protected $fillable = [
    'cft_id',
    'from_km',
    'to_km',
    'km_rate',        
    'rate_type',
    'km_profit',
    'appicable_date'
   ];

    public function cft()
    {
        return $this->belongsTo(CFTModel::class, 'cft_id', 'id');
    }
}
