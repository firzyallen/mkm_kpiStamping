<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeldingNgDetail extends Model
{
    // Specify the table name if it's different from the plural form of the model name
    protected $table = 'welding_ng_details';

    // Since this is a view, you don't need timestamps
    public $timestamps = false;

    // If your view has a primary key, specify it here
    // If not, you can remove this line
    //protected $primaryKey = 'id';

    // Since this is a view, you should specify that the table doesn't have an incrementing ID
    public $incrementing = false;

    // If the primary key is not an integer, specify its type
    //protected $keyType = 'string';

    // List all the columns in your view that you want to be able to access
    protected $fillable = [
        'date',
        'shift',
        'shop_id',
        'shop_name',
        'model_id',
        'model_name',
        'reject',
        'rework',
        'photo_ng',
        'remarks'
    ];
}