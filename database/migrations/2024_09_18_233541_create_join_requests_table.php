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
        Schema::create('join_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ins_comercial_name', 128);
            $table->string('ins_address', 256);
            $table->string('ins_hood', 128);
            $table->string('ins_cp', 10);
            $table->string('ins_email', 256);
            $table->integer('com_capacity');
            $table->integer('com_male');
            $table->integer('com_female');
            $table->integer('com_disabled');
            $table->date('com_open_date');
            $table->string('com_license_status', 2);
            $table->string('com_license_type', 2);
            $table->string('tax_name', 128);
            $table->string('tax_rfc', 16);
            $table->string('tax_street', 128);
            $table->string('tax_hood', 128);
            $table->string('tax_cp', 10);
            $table->string('tax_locality', 128);
            $table->string('tax_payment', 2);
            $table->string('con_name', 128);
            $table->string('con_role', 96);
            $table->string('con_phone', 13);
            $table->string('con_email', 256);
            $table->string('com_hours', 128);
            $table->string('com_line', 1024);
            $table->string('com_desc', 1024);
            $table->string('sm_facebook', 512);
            $table->string('sm_instagram', 512);
            $table->string('sm_twitter', 512);
            $table->string('sm_email', 256);
            $table->string('sm_phone', 13);
            $table->string('sm_web', 512);
            $table->string('sm_other', 512)->nullable();
            $table->boolean('sv_have_wifi');
            $table->boolean('sv_have_ac');
            $table->boolean('sv_have_live_music');
            $table->boolean('sv_have_deck');
            $table->boolean('sv_have_lounge');
            $table->integer('sv_lounge_capacity');
            $table->string('sv_other', 512)->nullable();
            $table->integer('status');
            $table->date('arrived_date')->nullable();
            $table->date('watched_date')->nullable();
            $table->date('contacted_date')->nullable();
            $table->date('finished_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_requests');
    }
};
