<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbFtt extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'factb_ftts';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'date',
        'shop_id',
        'shop_name',
        'Total_Prod',
        'reject',
        'rework',
        'FTT_Plan',
        'FTT'
    ];

    // Disable timestamps if they are not present in the table
    public $timestamps = false;

    // Indicate that the model does not have an auto-incrementing ID
    public $incrementing = false;

    // Set the primary key to null if there isn't a primary key
    protected $primaryKey = null;
    protected $keyType = 'string';
}