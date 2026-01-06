<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_genres', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->text('name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_genres');
    }
};


