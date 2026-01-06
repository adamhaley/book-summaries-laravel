<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('german_companies', function (Blueprint $table) {
            $table->id();

            $table->text('company')->nullable();
            $table->text('industry')->nullable();
            $table->text('ceo_name')->nullable();
            $table->text('phone')->nullable();
            $table->text('email')->nullable();
            $table->text('website')->nullable();
            $table->text('address')->nullable();
            $table->text('district')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();

            $table->timestampTz('created_at')->useCurrent();
            $table->string('analysis')->nullable();

            $table->foreignId('populated_by')->nullable()->constrained('finder_felix_executions');

            $table->text('location_link')->nullable();
            $table->timestampTz('updated_at')->nullable()->useCurrent();
            $table->boolean('exported_to_instantly')->default(false);
            $table->text('email_status')->nullable();
            $table->boolean('first_contact_sent')->default(false);
            $table->boolean('is_duplicate')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('german_companies');
    }
};


