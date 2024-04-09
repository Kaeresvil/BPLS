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
        Schema::create('business_informations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->string('business_address');
            $table->string('business_postal');
            $table->string('business_tel_no')->nullable();
            $table->string('business_email');
            $table->string('business_mobile_no');
            $table->string('business_area');
            $table->string('total_employee');
            $table->string('no_employee');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_informations');
    }
};
