<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbActualHeader extends Model
{
    use HasFactory;

    protected $table = 'factb_actual_headers';

    protected $fillable = [
        'date',
        'shift',
        'revision',
        'created_by',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the details for the header.
     */
    public function details()
    {
        return $this->hasMany(FactbActualDetail::class, 'header_id');
    }
}
