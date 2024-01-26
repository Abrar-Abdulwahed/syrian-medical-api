<?php

use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->json('location');
            $table->json('payment_method');
            $table->string('status')->default(OrderStatus::PENDING->value)->nullable();
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
