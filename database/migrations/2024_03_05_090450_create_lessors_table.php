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
        Schema::create('lessors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->string('lessors_fullname');
            $table->string('lessors_address');
            $table->string('lessors_tel_no');
            $table->string('lessors_email');
            $table->string('monthly_rental');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessors');
    }
};
