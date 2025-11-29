<?php

namespace App\Models;

use App\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_post_id',
        'candidate_id',
        'status',
        'cover_letter',
        'resume_url',
        'resume_text',
        'resume_embedding',
        'match_score',
        'screening_report',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'job_post_id' => 'integer',
            'candidate_id' => 'integer',
            'status' => ApplicationStatus::class,
            'match_score' => 'decimal:2',
            'screening_report' => 'array',
        ];
    }

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Check if screening has been completed.
     */
    public function hasScreeningCompleted(): bool
    {
        return ! is_null($this->match_score) && ! is_null($this->screening_report);
    }

    /**
     * Get match score as percentage.
     */
    public function getMatchScorePercentageAttribute(): ?float
    {
        return $this->match_score;
    }
}
