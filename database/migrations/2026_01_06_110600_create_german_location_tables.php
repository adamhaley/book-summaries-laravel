<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('german_cities', function (Blueprint $table) {
            $table->id();
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('german_districts', function (Blueprint $table) {
            $table->id();
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->text('district')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('german_districts');
        Schema::dropIfExists('german_cities');
    }
};


