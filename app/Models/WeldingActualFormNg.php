<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeldingActualFormNg extends Model
{
    use HasFactory;

    protected $table = 'welding_actual_form_ngs';

    protected $fillable = [
        'production_id',
        'total_prod',
        'reject',
        'rework',
        'remarks',
        'photo_ng',
    ];

    public function production()
    {
        return $this->belongsTo(WeldingActualFormProduction::class, 'production_id');
    }
}
