<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactbActualFormProduction extends Model
{
    use HasFactory;

    protected $table = 'factb_actual_form_productions';

    protected $fillable = [
        'details_id',
        'model_id',
        'hour',
        'output8',
        'output2',
        'output1',
        'plan_prod',
        'cabin',
        'PPM',
    ];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the detail that owns the form production.
     */
    public function detail()
    {
        return $this->belongsTo(FactbActualDetail::class, 'details_id');
    }

    /**
     * Get the model that owns the form production.
     */
    public function model()
    {
        return $this->belongsTo(FactbMstModel::class, 'model_id');
    }

    /**
     * Get the form NGs for the form production.
     */
    public function formNgs()
    {
        return $this->hasMany(FactbActualFormNg::class, 'production_id');
    }
}
