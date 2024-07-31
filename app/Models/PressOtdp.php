<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressOtdp extends Model
{
    protected $table = 'press_otdps';
    public $timestamps = false;

    // Define the fillable properties
    protected $fillable = [
        'date',
        'shop_name',
        'Total_Prod',
        'Plan_Prod',
        'OTDP_Plan',
        'OTDP'
    ];
}
