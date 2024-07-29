<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressActualFormProduction extends Model
{
    use HasFactory;

    protected $table = 'press_actual_form_productions';

    protected $fillable = [
        'details_id',
        'status',
        'model_id',
        'type',
        'inc_material',
        'machine',
        'setting',
        'hour_from',
        'hour_to',
        'plan_prod',
        'OK'
    ];

    public function details()
    {
        return $this->belongsTo(PressActualFormDetail::class, 'details_id');
    }

    public function model()
    {
        return $this->belongsTo(PressMstModel::class, 'model_id');
    }

    public function ngs()
    {
        return $this->hasMany(PressActualFormNg::class, 'production_id');
    }
}
