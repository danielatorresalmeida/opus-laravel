<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compositions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('composer_id')
                ->constrained('composers')
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('catalogue_number', 100)->nullable();
            $table->unsignedSmallInteger('composition_year')->nullable();
            $table->string('genre', 100)->nullable();
            $table->string('instrumentation')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('score_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compositions');
    }
};
