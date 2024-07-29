<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactbHpu extends Model
{
    protected $table = 'factb_hpus';
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