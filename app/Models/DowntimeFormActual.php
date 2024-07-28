<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormActual extends Model
{
    use HasFactory;

    protected $fillable = [
        'details_id', 'machine_id', 'category', 'shop_call', 'problem', 'cause', 'action', 'judgement', 'start_time', 'end_time', 'balance', 'percentage'
    ];

    public function machine()
    {
        return $this->belongsTo(DowntimeMstMachine::class, 'machine_id');
    }

    public function details()
    {
        return $this->belongsTo(DowntimeFormDetails::class, 'details_id');
    }
}
