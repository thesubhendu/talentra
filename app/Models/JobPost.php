<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'employment_type',
        'salary_min',
        'salary_max',
        'posted_at',
        'expires_at',
        'location',
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
            'posted_at' => 'timestamp',
            'expires_at' => 'timestamp',
        ];
    }
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)
            ->using(JobPostSkill::class)
            ->as('job_post_skill')
            ->withPivot('id', 'experience_level', 'years_of_experience', 'is_required')
            ->withTimestamps();
    }
}
