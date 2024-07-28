<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeMstMachine extends Model
{
    use HasFactory;

    protected $table = 'downtime_mst_machines'; // Specify the table name if it's not the default naming convention

    protected $fillable = [
        'shop_id',
        'shop_type',
        'machine_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function machine()
    {
        return $this->belongsTo(DowntimeMstMachine::class, 'machine_id');
    }

    // Define relationships, e.g., relationship with unified_shops
    public function shop()
    {
        return $this->belongsTo(UnifiedShop::class, 'shop_id', 'shop_id');
    }
}
