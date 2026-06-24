<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedLetter extends Model
{
    protected $fillable = ['submission_id', 'panitera_id', 'file_path'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function panitera(): BelongsTo
    {
        return $this->belongsTo(Panitera::class);
    }
}
