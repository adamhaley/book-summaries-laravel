<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('book_id')->nullable()->constrained('books');
            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->text('summary_md')->nullable();
            $table->integer('token_count')->nullable();
            $table->text('status')->nullable()->default('pending_chunk');
            $table->timestampTz('created_at')->nullable()->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrent();

            // Supabase dump uses USER-DEFINED for embedding (commonly pgvector).
            // We store it as jsonb until you confirm the type/dimensions.
            $table->jsonb('embedding')->nullable();

            $table->jsonb('metadata')->default(DB::raw("'{}'::jsonb"));
            $table->smallInteger('chapter_index')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};


