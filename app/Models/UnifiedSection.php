<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnifiedSection extends Model
{
    use HasFactory;

    protected $table = 'unified_sections';

    protected $fillable = [
        'section_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define the relationship with UnifiedShop
    public function shops()
    {
        return $this->hasMany(UnifiedShop::class, 'section_id');
    }
}
