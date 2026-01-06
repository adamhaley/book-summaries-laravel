<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->text('name')->nullable();
            $table->text('slug')->nullable();
            $table->text('body')->nullable();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->timestampTz('created_at')->nullable()->useCurrent();

            // Supabase dump uses USER-DEFINED for embedding (commonly pgvector).
            // We store it as jsonb until you confirm the type/dimensions.
            $table->jsonb('embedding')->nullable();
        });

        Schema::create('finder_felix_executions', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->text('execution')->nullable();
            $table->text('postal_code')->nullable();
            $table->smallInteger('num_results')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finder_felix_executions');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('email_templates');
    }
};


