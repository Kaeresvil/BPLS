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
        Schema::create('owner_informations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->string('owner_address');
            $table->string('owner_postal');
            $table->string('owner_tel_no')->nullable();
            $table->string('owner_email');
            $table->string('owner_mobile_no');
            $table->string('contact_person_mobile_no')->nullable();
            $table->string('contact_person_email');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_informations');
    }
};
