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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 256);
            $table->string('title_en', 256);
            $table->string('description_es', 2048);
            $table->string('description_en', 2048);
            $table->string('contact_name', 128);
            $table->string('phone', 13);
            $table->string('img_path', 512);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
