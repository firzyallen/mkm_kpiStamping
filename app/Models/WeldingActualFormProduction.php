<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingActualFormProduction extends Model
{
    use HasFactory;

    protected $table = 'welding_actual_form_productions';

    protected $fillable = [
        'station_details_id',
        'model_id',
        'hour',
        'output8',
        'output2',
        'output1',
        'plan_prod',
        'cabin',
        'PPM',
    ];

    public function stationDetail()
    {
        return $this->belongsTo(WeldingActualStationDetail::class, 'station_details_id');
    }

    public function model()
    {
        return $this->belongsTo(WeldingMstModel::class, 'model_id');
    }

    public function ngs()
    {
        return $this->hasMany(WeldingActualFormNg::class, 'production_id');
    }
}