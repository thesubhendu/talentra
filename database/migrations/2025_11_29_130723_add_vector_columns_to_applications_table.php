<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enable pgvector extension if not already enabled
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        // Add columns using raw SQL for vector type
        DB::statement('ALTER TABLE applications ADD COLUMN IF NOT EXISTS resume_text TEXT');
        DB::statement('ALTER TABLE applications ADD COLUMN IF NOT EXISTS resume_embedding vector(1536)');
        DB::statement('ALTER TABLE applications ADD COLUMN IF NOT EXISTS match_score DECIMAL(5,2)');
        DB::statement('ALTER TABLE applications ADD COLUMN IF NOT EXISTS screening_report JSONB');

        // Add index for vector similarity search
        DB::statement('CREATE INDEX IF NOT EXISTS applications_resume_embedding_idx ON applications USING ivfflat (resume_embedding vector_cosine_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS applications_resume_embedding_idx');
        DB::statement('ALTER TABLE applications DROP COLUMN IF EXISTS screening_report');
        DB::statement('ALTER TABLE applications DROP COLUMN IF EXISTS match_score');
        DB::statement('ALTER TABLE applications DROP COLUMN IF EXISTS resume_embedding');
        DB::statement('ALTER TABLE applications DROP COLUMN IF EXISTS resume_text');
    }
};
