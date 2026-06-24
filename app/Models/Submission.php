<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'name',
        'gender',
        'nim',
        'semester',
        'university',
        'faculty',
        'study_program',
        'email',
        'phone',
        'address',
        'title',
        'location',
        'target_institution',
        'purpose',
        'methodology',
        'start_date',
        'end_date',
        'current_status',
        'admin_notes',
        'permit_file_path'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(SubmissionStatus::class);
    }

    public function generatedLetter()
    {
        return $this->hasOne(GeneratedLetter::class);
    }
}
