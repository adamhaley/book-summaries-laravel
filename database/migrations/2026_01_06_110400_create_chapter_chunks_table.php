<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('chapter_id')->nullable()->constrained('chapters');
            $table->text('content')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestampTz('created_at')->nullable()->useCurrent();

            // Supabase dump uses USER-DEFINED for embedding (commonly pgvector).
            // We store it as jsonb until you confirm the type/dimensions.
            $table->jsonb('embedding')->nullable();

            $table->smallInteger('chunk_index')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_chunks');
    }
};


