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
        Schema::create('field_trips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->date('begin_date');
            $table->date('end_date');
            $table->timestamp('departure_time');
            $table->timestamp('return_time');
            $table->decimal('cost', 8, 2);
            $table->date('payment_deadline');
            $table->foreignId('class_id')->constrained();
            $table->enum('status', ['open', 'completed', 'cancelled'])->default('open'); // OPEN - COMPLETED - CANCELLED
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_trips');
    }
};
