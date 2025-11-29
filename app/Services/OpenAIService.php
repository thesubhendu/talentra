<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Smalot\PdfParser\Parser;

class OpenAIService
{
    /**
     * Extract text from a PDF file.
     * Uses PDF parser library first, then optionally cleans with OpenAI.
     */
    public function extractTextFromPdf(string $filePath): string
    {
        try {
            // Read the PDF file
            $fullPath = Storage::disk('public')->path($filePath);

            if (! file_exists($fullPath)) {
                throw new \RuntimeException("PDF file not found: {$filePath}");
            }

            // First, extract text using PDF parser
            $parser = new Parser;
            $pdf = $parser->parseFile($fullPath);
            $rawText = $pdf->getText();

            if (empty(trim($rawText))) {
                throw new \RuntimeException('Failed to extract text from PDF - PDF parser returned empty text');
            }

            // Clean and normalize the text using OpenAI
            // This helps remove formatting artifacts and improves text quality
            try {
                $cleanedText = $this->cleanExtractedText($rawText);

                return $cleanedText;
            } catch (\Exception $e) {
                // If OpenAI cleaning fails, return the raw extracted text
                Log::warning('Failed to clean extracted text with OpenAI, using raw text', [
                    'error' => $e->getMessage(),
                ]);

                return $rawText;
            }
        } catch (\Exception $e) {
            Log::error('Failed to extract text from PDF', [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException("Failed to extract text from PDF: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Clean extracted PDF text using OpenAI.
     */
    private function cleanExtractedText(string $rawText): string
    {
        // Truncate if too long (OpenAI has token limits)
        $maxLength = 10000; // Approximate token limit consideration
        if (strlen($rawText) > $maxLength) {
            $rawText = substr($rawText, 0, $maxLength).'...';
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a text cleaning assistant. Clean and normalize extracted PDF text, preserving all important information including personal details, work experience, education, skills, and contact information. Remove formatting artifacts, fix spacing issues, and organize the text logically. Return only the cleaned text without any additional commentary.',
                ],
                [
                    'role' => 'user',
                    'content' => "Clean and normalize this extracted PDF text:\n\n{$rawText}",
                ],
            ],
        ]);

        return $response->choices[0]->message->content ?? $rawText;
    }

    /**
     * Generate embedding for given text.
     */
    public function generateEmbedding(string $text): array
    {
        try {
            $response = OpenAI::embeddings()->create([
                'model' => config('openai.embedding_model', 'text-embedding-3-small'),
                'input' => $text,
            ]);

            return $response->embeddings[0]->embedding;
        } catch (\Exception $e) {
            Log::error('Failed to generate embedding', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text),
            ]);
            throw new \RuntimeException("Failed to generate embedding: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Generate chat completion (for AI reports).
     */
    public function generateChatCompletion(array $messages, ?string $model = null): string
    {
        try {
            $model = $model ?? config('openai.chat_model', 'gpt-4');

            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            Log::error('Failed to generate chat completion', [
                'error' => $e->getMessage(),
                'model' => $model,
            ]);
            throw new \RuntimeException("Failed to generate chat completion: {$e->getMessage()}", 0, $e);
        }
    }
}
