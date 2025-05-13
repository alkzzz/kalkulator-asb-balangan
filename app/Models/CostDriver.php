<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostDriver extends Model
{
    protected $table = 'cost_drivers';

    protected $fillable = [
        'struktur_asb_id',
        'label',
    ];

    public function struktur()
    {
        return $this->belongsTo(StrukturASB::class, 'struktur_asb_id');
    }
}
