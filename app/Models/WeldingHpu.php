<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeldingHpu extends Model
{
    protected $table = 'welding_hpus';
    public $timestamps = false;

    // Define the fillable properties
    protected $fillable = [
        'date',
        'shop_name',
        'manpower_plan',
        'manpower',
        'working_hour',
        'Total_Prod',
        'Plan_Prod',
        'HPU_Plan',
        'HPU'
    ];
}