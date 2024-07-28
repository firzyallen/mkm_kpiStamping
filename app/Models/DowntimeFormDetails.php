<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_id', 'shop_id', 'reporter'
    ];

    public function actuals()
    {
        return $this->hasMany(DowntimeFormActual::class, 'details_id');
    }

    public function shop()
    {
        return $this->belongsTo(UnifiedShop::class, 'shop_id');
    }
}
