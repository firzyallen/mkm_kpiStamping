<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbMstShop extends Model
{
    use HasFactory;

    protected $table = 'factb_mst_shops';

    protected $fillable = [
        'shop_name',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
