<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsbObjekTahun extends Model
{
    protected $table = 'asb_objek_tahun';

    protected $fillable = [
        'asb_id',
        'objek_belanja_id',
        'tahun',
        'persentase',
    ];

    public function asb()
    {
        return $this->belongsTo(StrukturASB::class, 'asb_id');
    }

    public function objekBelanja()
    {
        return $this->belongsTo(ObjekBelanja::class, 'objek_belanja_id');
    }
}
