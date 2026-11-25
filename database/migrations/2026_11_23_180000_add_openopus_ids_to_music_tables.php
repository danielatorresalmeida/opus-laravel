<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ID da biblioteca externa; os registos atuais ficam intactos (NULL).
        Schema::table('composers', function (Blueprint $table) {
            $table->unsignedInteger('openopus_id')->nullable()->unique();
        });

        Schema::table('compositions', function (Blueprint $table) {
            $table->unsignedInteger('openopus_id')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('compositions', function (Blueprint $table) {
            $table->dropUnique(['openopus_id']);
            $table->dropColumn('openopus_id');
        });

        Schema::table('composers', function (Blueprint $table) {
            $table->dropUnique(['openopus_id']);
            $table->dropColumn('openopus_id');
        });
    }
};
