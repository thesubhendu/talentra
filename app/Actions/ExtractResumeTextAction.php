<?php

namespace App\Actions;

use App\DTO\ResumeExtractionDTO;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\Log;

class ExtractResumeTextAction
{
    public function __construct(
        private OpenAIService $openAIService
    ) {}

    public function execute(string $resumePath): ResumeExtractionDTO
    {
        try {
            $text = $this->openAIService->extractTextFromPdf($resumePath);

            if (empty(trim($text))) {
                return ResumeExtractionDTO::failure('Extracted text is empty');
            }

            return ResumeExtractionDTO::success($text);
        } catch (\Exception $e) {
            Log::error('Failed to extract resume text', [
                'resume_path' => $resumePath,
                'error' => $e->getMessage(),
            ]);

            return ResumeExtractionDTO::failure($e->getMessage());
        }
    }
}
