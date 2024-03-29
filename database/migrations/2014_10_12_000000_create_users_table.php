<?php

use App\Enums\UserType;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->ipAddress('ip')->nullable();
            $table->string('email')->unique();
            $table->string('picture')->nullable();
            $table->string('type')->default(UserType::PATIENT->value);
            $table->boolean('activated')->default(1); //0: no 1: yes
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('verification_code')->nullable();
            $table->integer('login_attempts')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
