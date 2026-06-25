<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';
    protected $fillable = ['file_path', 'type', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
