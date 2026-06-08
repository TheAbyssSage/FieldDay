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
        Schema::dropIfExists('guardians');
        Schema::dropIfExists('teachers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('password');
            $table->foreignId('role_id')->constrained();
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
