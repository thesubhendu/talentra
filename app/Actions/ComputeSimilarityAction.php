<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComputeSimilarityAction
{
    /**
     * Compute cosine similarity between two embedding vectors.
     * Returns a score between 0 and 100.
     */
    public function execute(array $vector1, array $vector2): float
    {
        try {
            if (empty($vector1) || empty($vector2)) {
                return 0.0;
            }

            if (count($vector1) !== count($vector2)) {
                Log::warning('Vector dimensions do not match', [
                    'vector1_dim' => count($vector1),
                    'vector2_dim' => count($vector2),
                ]);

                return 0.0;
            }

            // Compute cosine similarity
            $dotProduct = 0;
            $magnitude1 = 0;
            $magnitude2 = 0;

            for ($i = 0; $i < count($vector1); $i++) {
                $dotProduct += $vector1[$i] * $vector2[$i];
                $magnitude1 += $vector1[$i] * $vector1[$i];
                $magnitude2 += $vector2[$i] * $vector2[$i];
            }

            $magnitude1 = sqrt($magnitude1);
            $magnitude2 = sqrt($magnitude2);

            if ($magnitude1 == 0 || $magnitude2 == 0) {
                return 0.0;
            }

            $similarity = $dotProduct / ($magnitude1 * $magnitude2);

            // Convert from [-1, 1] to [0, 100]
            // Cosine similarity ranges from -1 to 1, but embeddings are typically positive
            // So we normalize to 0-100 range
            $score = (($similarity + 1) / 2) * 100;

            return round($score, 2);
        } catch (\Exception $e) {
            Log::error('Failed to compute similarity', [
                'error' => $e->getMessage(),
            ]);

            return 0.0;
        }
    }

    /**
     * Compute similarity using PostgreSQL pgvector.
     */
    public function executeWithPostgres(string $table1, string $column1, int $id1, string $table2, string $column2, int $id2): ?float
    {
        try {
            // Use PostgreSQL's cosine distance function (<=> operator)
            // The operator returns distance (0 = identical, 2 = opposite)
            // We convert to similarity: 1 - distance gives us a value from -1 to 1
            // Then we scale to 0-100
            $result = DB::selectOne("
                SELECT 1 - (a.{$column1} <=> b.{$column2}) as similarity
                FROM {$table1} a, {$table2} b
                WHERE a.id = ? AND b.id = ?
                AND a.{$column1} IS NOT NULL AND b.{$column2} IS NOT NULL
            ", [$id1, $id2]);

            if ($result && isset($result->similarity)) {
                // Similarity is already in [-1, 1] range from cosine similarity
                // Convert to [0, 100] percentage
                $score = (($result->similarity + 1) / 2) * 100;

                return round(max(0, min(100, $score)), 2);
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to compute similarity with PostgreSQL', [
                'error' => $e->getMessage(),
                'table1' => $table1,
                'table2' => $table2,
                'id1' => $id1,
                'id2' => $id2,
            ]);

            return null;
        }
    }
}
