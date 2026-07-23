<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';
    
    protected $fillable = [
        'name',
        'institution_type',
        'template_type',
        'file_path',
        'version',
        'is_active',
        'uploaded_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'version' => 'integer'
    ];

    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
