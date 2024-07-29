<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressActualFormDetail extends Model
{
    use HasFactory;

    protected $table = 'press_actual_form_details';

    protected $fillable = [
        'header_id',
        'shop_id',
        'manpower',
        'manpower_plan',
        'working_hour',
        'notes',
        'photo_shop'
    ];

    public function header()
    {
        return $this->belongsTo(PressActualHeader::class, 'header_id');
    }

    public function shop()
    {
        return $this->belongsTo(PressMstShop::class, 'shop_id');
    }

    public function productions()
    {
        return $this->hasMany(PressActualFormProduction::class, 'details_id');
    }
}
