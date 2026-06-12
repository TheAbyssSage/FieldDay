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
        Schema::table('field_trips', function (Blueprint $table) {
            $table->foreignId('permission_form_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('field_trips', function (Blueprint $table) {
            $table->dropConstrainedForeignId('permission_form_id');
        });
    }
};
