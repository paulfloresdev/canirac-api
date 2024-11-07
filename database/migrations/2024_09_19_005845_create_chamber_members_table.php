<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chamber_members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('role_es', 128);
            $table->string('role_en', 128);
            $table->string('img_path', 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamber_members');
    }
};
