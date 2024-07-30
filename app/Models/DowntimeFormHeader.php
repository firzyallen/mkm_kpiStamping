<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeFormHeader extends Model
{
    use HasFactory;

    protected $table = 'downtime_form_headers';

    protected $fillable = [
        'section_id', 'shift', 'date', 'revision', 'created_by',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with DowntimeFormDetail
    public function details()
    {
        return $this->hasMany(DowntimeFormDetail::class, 'header_id');
    }

    // Define the relationship with UnifiedSection
    public function section()
    {
        return $this->belongsTo(UnifiedSection::class, 'section_id');
    }
}
