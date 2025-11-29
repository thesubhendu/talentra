<?php

namespace App\Actions;

use App\DTO\EmbeddingDTO;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateEmbeddingAction
{
    public function __construct(
        private OpenAIService $openAIService
    ) {}

    public function execute(string $text): EmbeddingDTO
    {
        try {
            if (empty(trim($text))) {
                return EmbeddingDTO::failure('Text is empty');
            }

            $vector = $this->openAIService->generateEmbedding($text);

            if (empty($vector)) {
                return EmbeddingDTO::failure('Generated embedding is empty');
            }

            return EmbeddingDTO::success($vector);
        } catch (\Exception $e) {
            Log::error('Failed to generate embedding', [
                'error' => $e->getMessage(),
                'text_length' => strlen($text),
            ]);

            return EmbeddingDTO::failure($e->getMessage());
        }
    }

    /**
     * Store embedding in database.
     * Uses PostgreSQL vector type.
     */
    public function storeEmbedding(string $table, string $column, int $id, array $vector): void
    {
        // For PostgreSQL with pgvector, store as vector type
        $vectorString = '['.implode(',', $vector).']';
        DB::statement("UPDATE {$table} SET {$column} = ?::vector WHERE id = ?", [$vectorString, $id]);
    }
}
