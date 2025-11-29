<?php

namespace App\Services;

use App\Actions\GenerateEmbeddingAction;
use App\Models\Application;
use App\Models\JobPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmbeddingService
{
    public function __construct(
        private GenerateEmbeddingAction $generateEmbeddingAction
    ) {}

    /**
     * Generate and store embedding for a job post.
     */
    public function generateJobEmbedding(JobPost $jobPost): bool
    {
        try {
            // Build text from job post
            $text = $this->buildJobText($jobPost);

            // Generate embedding
            $embeddingDTO = $this->generateEmbeddingAction->execute($text);

            if (! $embeddingDTO->isSuccessful()) {
                Log::error('Failed to generate job embedding', [
                    'job_post_id' => $jobPost->id,
                    'error' => $embeddingDTO->error,
                ]);

                return false;
            }

            // Store embedding
            $this->storeJobEmbedding($jobPost->id, $embeddingDTO->vector);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to generate job embedding', [
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate and store embedding for an application resume.
     */
    public function generateResumeEmbedding(Application $application): bool
    {
        try {
            $resumeText = $application->resume_text;

            if (empty($resumeText)) {
                Log::warning('Cannot generate embedding: resume text is empty', [
                    'application_id' => $application->id,
                ]);

                return false;
            }

            // Generate embedding
            $embeddingDTO = $this->generateEmbeddingAction->execute($resumeText);

            if (! $embeddingDTO->isSuccessful()) {
                Log::error('Failed to generate resume embedding', [
                    'application_id' => $application->id,
                    'error' => $embeddingDTO->error,
                ]);

                return false;
            }

            // Store embedding
            $this->storeResumeEmbedding($application->id, $embeddingDTO->vector);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to generate resume embedding', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Build text representation of a job post for embedding.
     */
    private function buildJobText(JobPost $jobPost): string
    {
        try {
            $parts = [
                $jobPost->title,
                $jobPost->description,
            ];

            // Add skills - load relationship if needed
            $jobPost->loadMissing('skills');
            $skills = $jobPost->skills->pluck('name')->join(', ');
            if (! empty($skills)) {
                $parts[] = "Required skills: {$skills}";
            }

            return implode("\n\n", array_filter($parts));
        } catch (\Exception $e) {
            Log::error('Failed to build job text', [
                'job_post_id' => $jobPost->id,
                'error' => $e->getMessage(),
            ]);

            // Return basic text without skills if there's an error
            return implode("\n\n", array_filter([
                $jobPost->title,
                $jobPost->description,
            ]));
        }
    }

    /**
     * Store job embedding in database.
     */
    private function storeJobEmbedding(int $jobPostId, array $vector): void
    {
        $vectorString = '['.implode(',', $vector).']';
        DB::statement('UPDATE job_posts SET job_embedding = ?::vector WHERE id = ?', [$vectorString, $jobPostId]);
    }

    /**
     * Store resume embedding in database.
     */
    private function storeResumeEmbedding(int $applicationId, array $vector): void
    {
        $vectorString = '['.implode(',', $vector).']';
        DB::statement('UPDATE applications SET resume_embedding = ?::vector WHERE id = ?', [$vectorString, $applicationId]);
    }
}
