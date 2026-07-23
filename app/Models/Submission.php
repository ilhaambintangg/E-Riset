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
        'permit_file_path',
        'is_read',
        'recipient_position',
        'destination_city',
        'reference_letter_number',
        'reference_letter_date',
        'research_title',
        'research_location',
        'research_type',
        'konsentrasi',
        'hakim_id',
        'waktu_penelitian',
        'interview_date'
    ];

    protected $casts = [
        'interview_date' => 'datetime',
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

    public function members(): HasMany
    {
        return $this->hasMany(SubmissionMember::class);
    }

    public function hakim()
    {
        return $this->belongsTo(Hakim::class, 'hakim_id');
    }

    public function isPt(): bool
    {
        $loc = strtolower($this->research_location ?? $this->location ?? '');
        return str_contains($loc, 'tinggi') || str_contains($loc, 'pt');
    }

    public function isPn(): bool
    {
        return !$this->isPt();
    }
}
