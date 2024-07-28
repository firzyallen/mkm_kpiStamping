<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressMstModel extends Model
{
    use HasFactory;

    protected $table = 'press_mst_models';

    protected $fillable = [
        'model_name',
        'shop_id',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the shop that owns the model.
     */
    public function shop()
    {
        return $this->belongsTo(PressMstShop::class, 'shop_id');
    }
}