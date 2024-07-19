<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressMstShop extends Model
{
    use HasFactory;

    protected $table = 'press_mst_shops';

    protected $fillable = [
        'shop_name',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
