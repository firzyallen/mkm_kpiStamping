<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbActualFormNg extends Model
{
    use HasFactory;

    protected $table = 'factb_actual_form_ngs';

    protected $fillable = [
        'production_id',
        'model_id',
        'total_prod',
        'reject',
        'rework',
        'remarks',
        'photo_ng',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the form production that owns the form NG.
     */
    public function production()
    {
        return $this->belongsTo(FactbActualFormProduction::class, 'production_id');
    }

    /**
     * Get the model that owns the form NG.
     */
    public function model()
    {
        return $this->belongsTo(FactbMstModel::class, 'model_id');
    }
}
