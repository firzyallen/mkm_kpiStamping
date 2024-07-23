<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingActualStationDetail extends Model
{
    use HasFactory;

    protected $table = 'welding_actual_station_details';

    protected $fillable = [
        'details_id',
        'station_id',
        'manpower_station',
    ];

    public function detail()
    {
        return $this->belongsTo(WeldingActualDetail::class, 'details_id');
    }

    public function station()
    {
        return $this->belongsTo(WeldingMstStation::class, 'station_id');
    }

    public function productions()
    {
        return $this->hasMany(WeldingActualFormProduction::class, 'station_details_id');
    }
}
