<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panitera extends Model
{
    protected $table = 'panitera';
    protected $fillable = ['nama_panitera', 'nip', 'jabatan', 'status_aktif'];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];
}
