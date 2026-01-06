<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Requires pgvector extension (Homebrew pgvector supports Postgres 17/18).
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        // The initial port used jsonb placeholders for embeddings. We intentionally
        // drop/recreate these columns as vector(768) since embeddings can be regenerated.
        DB::statement('ALTER TABLE chapters DROP COLUMN IF EXISTS embedding');
        DB::statement('ALTER TABLE chapters ADD COLUMN embedding vector(768)');

        DB::statement('ALTER TABLE chapter_chunks DROP COLUMN IF EXISTS embedding');
        DB::statement('ALTER TABLE chapter_chunks ADD COLUMN embedding vector(768)');
    }

    public function down(): void
    {
        // Reverse to jsonb to preserve broad compatibility.
        DB::statement('ALTER TABLE chapter_chunks DROP COLUMN IF EXISTS embedding');
        DB::statement('ALTER TABLE chapter_chunks ADD COLUMN embedding jsonb');

        DB::statement('ALTER TABLE chapters DROP COLUMN IF EXISTS embedding');
        DB::statement('ALTER TABLE chapters ADD COLUMN embedding jsonb');
    }
};


