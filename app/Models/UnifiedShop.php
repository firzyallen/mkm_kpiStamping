<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnifiedShop extends Model
{
    use HasFactory;

    protected $table = 'unified_shops'; // specify the table name if it's different from the default
    
    protected $fillable = [
        'shop_name',
        'shop_type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = ['shop_id', 'shop_type']; // composite primary key

    public $incrementing = false; // composite keys are not auto-incrementing

    public function machines()
    {
        return $this->hasMany(DowntimeMstMachine::class, 'shop_id', 'shop_id')
                    ->where('shop_type', $this->shop_type);
    }
}
