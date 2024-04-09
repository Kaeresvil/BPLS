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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('applicant_id');
            $table->string('ref_no');
            $table->string('status');
            $table->string('type_of_application');
            $table->string('mode_of_payment');
            $table->date('date_of_application');
            $table->string('tin');
            $table->string('type_of_business');
            $table->string('ammendment_from')->nullable();
            $table->string('ammendment_to')->nullable();
            $table->string('dti_registration_no');
            $table->date('dti_registration_date');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('business_name');
            $table->string('trade_name');
            $table->bigInteger('actioned_by_id')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
