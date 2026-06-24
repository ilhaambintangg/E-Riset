<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermohonanPenelitian extends Model
{
    use HasFactory;

    protected $table = 'permohonan_penelitian';

    protected $fillable = [
        'nama_lengkap',
        'jenis_kelamin',
        'email',
        'no_hp',
        'asal_universitas',
        'surat_pengantar',
        'proposal_penelitian',
        'status',
    ];
}
