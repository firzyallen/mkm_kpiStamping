<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingActualDetail extends Model
{
    use HasFactory;

    protected $table = 'welding_actual_details';

    protected $fillable = [
        'header_id',
        'shop_id',
        'manpower',
        'manpower_plan',
        'working_hour',
        'ot_hour',
        'ot_hour_plan',
        'notes',
        'photo_shop',
    ];

    public function header()
    {
        return $this->belongsTo(WeldingActualHeader::class, 'header_id');
    }

    public function shop()
    {
        return $this->belongsTo(WeldingMstShop::class, 'shop_id');
    }

    public function stationDetails()
    {
        return $this->hasMany(WeldingActualStationDetail::class, 'details_id');
    }
}
