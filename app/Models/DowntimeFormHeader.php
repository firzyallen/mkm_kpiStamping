<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_type', 'shift', 'date', 'created_by'
    ];

    public function details()
    {
        return $this->hasMany(DowntimeFormDetails::class, 'header_id');
    }
}
