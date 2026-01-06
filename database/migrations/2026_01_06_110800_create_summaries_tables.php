<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Canonical Laravel port: use bigint users.id instead of Supabase auth.users(uuid).
            $table->foreignId('user_id')->constrained('users');

            $table->foreignUuid('book_id')->constrained('books');

            $table->string('style');
            $table->string('length');
            $table->text('file_path');
            $table->integer('tokens_spent')->nullable();
            $table->decimal('generation_time', 12, 3)->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });

        Schema::create('summaries_v2', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('book_id')->constrained('books');

            $table->jsonb('summary');

            // Supabase uses CHECK constraints. Laravel's enum maps cleanly to Postgres.
            $table->enum('length', ['short', 'medium', 'long']);
            $table->enum('style', ['narrative', 'bullet_points', 'workbook']);

            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();

            $table->text('complete_book_summary')->nullable();
            $table->jsonb('formatted_summary')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summaries_v2');
        Schema::dropIfExists('summaries');
    }
};


