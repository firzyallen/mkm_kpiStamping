<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingMstModel extends Model
{
    use HasFactory;

    // Specify the table if it does not follow Laravel's naming convention
    protected $table = 'welding_mst_models';

    // The attributes that are mass assignable
    protected $fillable = [
        'station_id',
        'model_name',
    ];

    // Automatically manage created_at and updated_at columns
    public $timestamps = true;

    // Define the relationship to the WeldingMstStation
    public function station()
    {
        return $this->belongsTo(WeldingMstStation::class, 'station_id');
    }
}
