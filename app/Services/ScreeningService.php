<?php

namespace App\Services;

use App\Actions\ComputeSimilarityAction;
use App\Actions\ExtractResumeTextAction;
use App\Actions\GenerateEmbeddingAction;
use App\Actions\GenerateScreeningReportAction;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class ScreeningService
{
    public function __construct(
        private ExtractResumeTextAction $extractResumeTextAction,
        private GenerateEmbeddingAction $generateEmbeddingAction,
        private ComputeSimilarityAction $computeSimilarityAction,
        private GenerateScreeningReportAction $generateScreeningReportAction,
        private EmbeddingService $embeddingService
    ) {}

    /**
     * Process the full screening pipeline for an application.
     */
    public function processApplication(Application $application): bool
    {
        try {
            Log::info('Starting screening process', ['application_id' => $application->id]);

            // Step 1: Extract resume text
            if (empty($application->resume_text)) {
                $this->extractResumeText($application);
            }

            // Step 2: Generate resume embedding
            if (empty($application->resume_embedding)) {
                $this->generateResumeEmbedding($application);
            }

            // Step 3: Ensure job has embedding
            $jobPost = $application->jobPost;
            if (! $jobPost->hasEmbedding()) {
                $this->embeddingService->generateJobEmbedding($jobPost);
                $jobPost->refresh();
            }

            // Step 4: Compute similarity score
            $this->computeSimilarity($application);

            // Step 5: Generate screening report
            $this->generateScreeningReport($application);

            Log::info('Screening process completed', ['application_id' => $application->id]);

            return true;
        } catch (\Exception $e) {
            Log::error('Screening process failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Extract text from resume PDF.
     */
    private function extractResumeText(Application $application): void
    {
        if (empty($application->resume_url)) {
            throw new \RuntimeException('Resume URL is not available');
        }

        $result = $this->extractResumeTextAction->execute($application->resume_url);

        if (! $result->isSuccessful()) {
            throw new \RuntimeException("Failed to extract resume text: {$result->error}");
        }

        $application->resume_text = $result->text;
        $application->save();
    }

    /**
     * Generate embedding for resume.
     */
    private function generateResumeEmbedding(Application $application): void
    {
        if (empty($application->resume_text)) {
            throw new \RuntimeException('Resume text is not available');
        }

        $result = $this->generateEmbeddingAction->execute($application->resume_text);

        if (! $result->isSuccessful()) {
            throw new \RuntimeException("Failed to generate resume embedding: {$result->error}");
        }

        // Store embedding
        $this->embeddingService->generateResumeEmbedding($application);
        $application->refresh();
    }

    /**
     * Compute similarity score between resume and job.
     */
    private function computeSimilarity(Application $application): void
    {
        $jobPost = $application->jobPost;

        // Verify both embeddings exist
        if (empty($application->resume_embedding)) {
            throw new \RuntimeException('Resume embedding is not available');
        }

        if (empty($jobPost->job_embedding)) {
            throw new \RuntimeException('Job embedding is not available');
        }

        // Use PostgreSQL vector similarity
        $score = $this->computeSimilarityAction->executeWithPostgres(
            'applications',
            'resume_embedding',
            $application->id,
            'job_posts',
            'job_embedding',
            $jobPost->id
        );

        if ($score === null) {
            throw new \RuntimeException('Failed to compute similarity score');
        }

        $application->match_score = $score;
        $application->save();
    }

    /**
     * Generate screening report.
     */
    private function generateScreeningReport(Application $application): void
    {
        $result = $this->generateScreeningReportAction->execute($application);

        if (! $result->isSuccessful()) {
            throw new \RuntimeException("Failed to generate screening report: {$result->error}");
        }

        $application->screening_report = $result->toArray();
        $application->save();
    }
}
