<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            // Canonical Laravel port: use bigint users.id instead of Supabase auth.users(uuid).
            $table->foreignId('user_id')->unique()->constrained('users');

            $table->jsonb('preferences')->default(DB::raw("'{\"style\": \"narrative\", \"length\": \"5pg\"}'::jsonb"));
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->text('role')->default('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};


