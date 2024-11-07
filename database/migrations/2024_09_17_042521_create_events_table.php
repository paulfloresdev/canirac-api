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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_es', 256);
            $table->string('title_en', 256);
            $table->string('description_es', 2048);
            $table->string('description_en', 2048);
            $table->double('price')->nullable();
            $table->date('date');
            $table->string('time', 32);
            $table->string('address', 256);
            $table->double('lat')->nullable()->nullable();
            $table->double('long')->nullable()->nullable();
            $table->string('img_path', 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
