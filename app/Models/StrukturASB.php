<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturASB extends Model
{
    protected $table = 'struktur_asb';

    protected $fillable = [
        'kode',
        'nama',
        'definisi',
        'fixed_cost',
        'variable_cost',
    ];

    public function costDrivers()
    {
        return $this->hasMany(\App\Models\CostDriver::class, 'struktur_asb_id');
    }

    public function objekTahunan()
    {
        return $this->hasMany(AsbObjekTahun::class, 'asb_id');
    }

    public function riwayatBelanja()
    {
        return $this->hasMany(RiwayatBelanja::class, 'asb_id');
    }
}
