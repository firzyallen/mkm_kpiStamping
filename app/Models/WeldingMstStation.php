<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingMstStation extends Model
{
    use HasFactory;

    // Specify the table if it does not follow Laravel's naming convention
    protected $table = 'welding_mst_stations';

    // The attributes that are mass assignable
    protected $fillable = [
        'shop_id',
        'station_name',
    ];

    // Automatically manage created_at and updated_at columns
    public $timestamps = true;

    // Define the relationship to the WeldingMstShop
    public function shop()
    {
        return $this->belongsTo(WeldingMstShop::class, 'shop_id');
    }

    // Define the relationship to the WeldingMstModel
    public function models()
    {
        return $this->hasMany(WeldingMstModel::class, 'station_id');
    }
}
