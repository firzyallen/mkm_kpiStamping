<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeMstMachine extends Model
{
    use HasFactory;

    protected $table = 'downtime_mst_machines';

    protected $fillable = [
        'shop_id', 'machine_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with UnifiedShop
    public function shop()
    {
        return $this->belongsTo(UnifiedShop::class, 'shop_id');
    }

    // Define the relationship with DowntimeFormActual
    public function downtimeActuals()
    {
        return $this->hasMany(DowntimeFormActual::class, 'machine_id');
    }
}
