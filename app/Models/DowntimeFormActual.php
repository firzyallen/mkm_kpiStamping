<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormActual extends Model
{
    use HasFactory;

    protected $table = 'downtime_form_actuals';

    protected $fillable = [
        'details_id', 'machine_id', 'photo', 'category', 'shop_call', 'problem', 'cause', 'action', 'judgement', 'start_time', 'end_time', 'balance', 'percentage'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with DowntimeFormDetail
    public function detail()
    {
        return $this->belongsTo(DowntimeFormDetail::class, 'details_id');
    }

    // Define the relationship with DowntimeMstMachine
    public function machine()
    {
        return $this->belongsTo(DowntimeMstMachine::class, 'machine_id');
    }
}
