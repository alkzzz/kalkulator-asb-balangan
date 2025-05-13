<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariabelASB extends Model
{
    protected $table = 'variabel_asb';

    protected $fillable = [
        'struktur_asb_id',
        'kode',
        'nama',
        'koefisien_b',
    ];

    public function struktur()
    {
        return $this->belongsTo(StrukturASB::class, 'struktur_asb_id');
    }
}
