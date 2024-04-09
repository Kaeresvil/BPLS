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
        Schema::create('business_activity', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id');
            $table->string('line_of_business');
            $table->string('units');
            $table->string('capitalization')->nullable();
            $table->string('essential')->nullable();
            $table->string('non_essential')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_activity');
    }
};
