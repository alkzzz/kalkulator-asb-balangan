<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjekBelanja extends Model
{
    protected $table = 'objek_belanja';

    protected $fillable = [
        'nama_objek',
    ];

    public function riwayatAsb()
    {
        return $this->hasMany(AsbObjekTahun::class, 'objek_belanja_id');
    }
}
