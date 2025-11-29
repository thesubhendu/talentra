<?php

namespace App\Actions;

use App\DTO\ScreeningReportDTO;
use App\Models\Application;
use App\Models\JobPost;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Log;

class GenerateScreeningReportAction
{
    public function __construct(
        private OpenAIService $openAIService
    ) {}

    public function execute(Application $application): ScreeningReportDTO
    {
        try {
            $jobPost = $application->jobPost;
            $resumeText = $application->resume_text;
            $coverLetter = $application->cover_letter ?? '';

            if (empty($resumeText)) {
                return ScreeningReportDTO::failure('Resume text is not available');
            }

            // Build prompt for OpenAI
            $prompt = $this->buildPrompt($jobPost, $resumeText, $coverLetter);

            $response = $this->openAIService->generateChatCompletion([
                [
                    'role' => 'system',
                    'content' => 'You are an expert recruiter and hiring manager. Analyze resumes and provide detailed screening reports. Always respond with valid JSON only, no additional text.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ]);

            // Parse JSON response
            $reportData = $this->parseResponse($response);

            return ScreeningReportDTO::success(
                $reportData['summary'] ?? '',
                $reportData['strengths'] ?? [],
                $reportData['weaknesses'] ?? [],
                $reportData['suggested_questions'] ?? []
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate screening report', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            return ScreeningReportDTO::failure($e->getMessage());
        }
    }

    private function buildPrompt(JobPost $jobPost, string $resumeText, string $coverLetter): string
    {
        $jobDescription = $jobPost->description;
        $jobTitle = $jobPost->title;
        $skills = $jobPost->skills->pluck('name')->join(', ');

        $prompt = "Analyze this candidate's resume and cover letter for the following job position.\n\n";
        $prompt .= "Job Title: {$jobTitle}\n";
        $prompt .= "Job Description: {$jobDescription}\n";

        if (! empty($skills)) {
            $prompt .= "Required Skills: {$skills}\n";
        }

        $prompt .= "\nResume Text:\n{$resumeText}\n";

        if (! empty($coverLetter)) {
            $prompt .= "\nCover Letter:\n{$coverLetter}\n";
        }

        $prompt .= "\nPlease provide a comprehensive screening report in the following JSON format:\n";
        $prompt .= "{\n";
        $prompt .= '  "summary": "A brief 2-3 sentence summary of the candidate\'s experience and fit for this role",'."\n";
        $prompt .= '  "strengths": ["Strength 1", "Strength 2", "Strength 3"],'."\n";
        $prompt .= '  "weaknesses": ["Weakness 1", "Weakness 2"],'."\n";
        $prompt .= '  "suggested_questions": ["Question 1", "Question 2", "Question 3", "Question 4", "Question 5"]'."\n";
        $prompt .= "}\n\n";
        $prompt .= 'Focus on: relevant experience, skills match, education, career progression, and potential red flags. Return ONLY valid JSON, no additional text.';

        return $prompt;
    }

    private function parseResponse(string $response): array
    {
        // Try to extract JSON from response (in case there's extra text)
        $jsonStart = strpos($response, '{');
        $jsonEnd = strrpos($response, '}');

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($response, $jsonStart, $jsonEnd - $jsonStart + 1);
            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
        }

        // Fallback: try parsing the whole response
        $data = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $data;
        }

        // If JSON parsing fails, return a structured error response
        Log::warning('Failed to parse screening report JSON', [
            'response' => substr($response, 0, 500),
        ]);

        throw new \RuntimeException('Failed to parse screening report response as JSON');
    }
}
