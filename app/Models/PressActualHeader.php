<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressActualHeader extends Model
{
    use HasFactory;

    protected $table = 'press_actual_headers';

    protected $fillable = [
        'date',
        'shift',
        'PIC',
        'revision',
        'created_by'
    ];

    public function details()
    {
        return $this->hasMany(PressActualFormDetail::class, 'header_id');
    }
}
