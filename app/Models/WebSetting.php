<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    protected $table = 'web_settings';
    
    protected $fillable = [
        'nama_instansi',
        'alamat',
        'telepon',
        'email',
        'website',
        'google_maps',
        'link_terkait'
    ];

    protected $casts = [
        'link_terkait' => 'array'
    ];
}
