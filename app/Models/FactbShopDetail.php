<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactbShopDetail extends Model
{
    // Specify the table name
    protected $table = 'factb_shop_details';

    // Set primary key if different, otherwise, Laravel defaults to 'id'
    // protected $primaryKey = 'id';

    // Disable auto-incrementing as views do not have an auto-incrementing primary key
    public $incrementing = false;

    // If the primary key is not an integer, set this to false
    //protected $keyType = 'string';

    // Disable timestamps if they are not available in the view
    public $timestamps = false;
}