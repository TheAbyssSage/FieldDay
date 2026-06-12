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
        Schema::create('permission_form_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_form_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('student_id')->constrained();
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_form_signatures');
    }
};
