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

        // Add job_embedding column using raw SQL for vector type
        DB::statement('ALTER TABLE job_posts ADD COLUMN IF NOT EXISTS job_embedding vector(1536)');

        // Add index for vector similarity search
        DB::statement('CREATE INDEX IF NOT EXISTS job_posts_job_embedding_idx ON job_posts USING ivfflat (job_embedding vector_cosine_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS job_posts_job_embedding_idx');
        DB::statement('ALTER TABLE job_posts DROP COLUMN IF EXISTS job_embedding');
    }
};
