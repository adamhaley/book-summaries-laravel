<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->text('title');
            $table->text('author')->nullable();
            $table->text('status')->nullable()->default('pending_ingest');
            $table->timestampTz('created_at')->nullable()->useCurrent();
            $table->timestampTz('updated_at')->nullable()->useCurrent();

            $table->foreignId('book_genre_id')->nullable()->constrained('book_genres');

            $table->text('summary')->nullable();
            $table->string('isbn')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->integer('publication_year')->nullable();
            $table->integer('page_count')->nullable();
            $table->text('default_summary_pdf_url')->nullable();
            $table->boolean('live')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};


