<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingMstShop extends Model
{
    use HasFactory;

    // Specify the table if it does not follow Laravel's naming convention
    protected $table = 'welding_mst_shops';

    // The attributes that are mass assignable
    protected $fillable = [
        'shop_name',
    ];

    // Automatically manage created_at and updated_at columns
    public $timestamps = true;
}
