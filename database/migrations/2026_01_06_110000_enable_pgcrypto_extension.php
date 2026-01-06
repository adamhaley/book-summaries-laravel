<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Needed for gen_random_uuid() defaults used by several tables.
        DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto');
    }

    public function down(): void
    {
        // Intentionally not dropped; other objects may depend on it.
    }
};


