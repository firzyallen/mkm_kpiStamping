<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbActualDetail extends Model
{
    use HasFactory;

    protected $table = 'factb_actual_details';

    protected $fillable = [
        'header_id',
        'shop_id',
        'manpower',
        'manpower_plan',
        'working_hour',
        'ot_hour',
        'ot_hour_plan',
        'notes',
        'photo_shop',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the header that owns the detail.
     */
    public function header()
    {
        return $this->belongsTo(FactbActualHeader::class, 'header_id');
    }

    /**
     * Get the shop that owns the detail.
     */
    public function shop()
    {
        return $this->belongsTo(FactbMstShop::class, 'shop_id');
    }

    /**
     * Get the form productions for the detail.
     */
    public function formProductions()
    {
        return $this->hasMany(FactbActualFormProduction::class, 'details_id');
    }
}
