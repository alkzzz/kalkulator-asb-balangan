<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    protected $table = 'skpd';

    protected $fillable = [
        'nama',
        'singkatan',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
