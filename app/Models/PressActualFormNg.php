<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressActualFormNg extends Model
{
    use HasFactory;

    protected $table = 'press_actual_form_ngs';

    protected $fillable = [
        'production_id',
        'model_id',
        'OK',
        'rework',
        'dmg_part',
        'dmg_rm',
        'remarks',
        'photo_ng'
    ];

    public function production()
    {
        return $this->belongsTo(PressActualFormProduction::class, 'production_id');
    }

    public function model()
    {
        return $this->belongsTo(PressMstModel::class, 'model_id');
    }
}
