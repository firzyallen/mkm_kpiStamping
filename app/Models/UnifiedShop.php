<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnifiedShop extends Model
{
    use HasFactory;

    protected $table = 'unified_shops';

    protected $fillable = [
        'section_id', 'shop_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with UnifiedSection
    public function section()
    {
        return $this->belongsTo(UnifiedSection::class, 'section_id');
    }

    // Define the relationship with DowntimeMstMachine
    public function machines()
    {
        return $this->hasMany(DowntimeMstMachine::class, 'shop_id');
    }

    // Define the relationship with DowntimeFormDetail
    public function downtimeDetails()
    {
        return $this->hasMany(DowntimeFormDetail::class, 'shop_id');
    }
}
