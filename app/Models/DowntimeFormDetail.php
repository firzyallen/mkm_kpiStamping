<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormDetail extends Model
{
    use HasFactory;

    protected $table = 'downtime_form_details';

    protected $fillable = [
        'header_id', 'shop_id', 'reporter'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with DowntimeFormHeader
    public function header()
    {
        return $this->belongsTo(DowntimeFormHeader::class, 'header_id');
    }

    // Define the relationship with UnifiedShop
    public function shop()
    {
        return $this->belongsTo(UnifiedShop::class, 'shop_id');
    }

    // Define the relationship with DowntimeFormActual
    public function downtimeActuals()
    {
        return $this->hasMany(DowntimeFormActual::class, 'details_id');
    }
}
