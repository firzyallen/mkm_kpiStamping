<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingActualHeader extends Model
{
    use HasFactory;

    protected $table = 'welding_actual_headers';

    protected $fillable = [
        'date',
        'shift',
        'pic',
        'revision',
        'created_by',
    ];

    public function details()
    {
        return $this->hasMany(WeldingActualDetail::class, 'header_id');
    }
}
