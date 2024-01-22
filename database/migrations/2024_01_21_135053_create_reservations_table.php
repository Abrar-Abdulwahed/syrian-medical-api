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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('reservationable');
            $table->foreignId('patient_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->json('location');
            $table->json('payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
