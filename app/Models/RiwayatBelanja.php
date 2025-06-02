<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatBelanja extends Model
{
    protected $table = 'riwayat_belanja';

    protected $fillable = [
        'skpd_id',
        'asb_id',
        'objek_belanja_id',
        'tahun',
        'persentase',
        'nilai_rupiah',
        'keterangan',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function asb()
    {
        return $this->belongsTo(StrukturASB::class);
    }

    public function objekBelanja()
    {
        return $this->belongsTo(ObjekBelanja::class);
    }
}
