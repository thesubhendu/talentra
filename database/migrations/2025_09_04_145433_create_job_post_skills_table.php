<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('job_post_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->string('experience_level')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_post_skills');
    }
};
