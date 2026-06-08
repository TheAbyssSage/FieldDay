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
        Schema::table('classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('classrooms', 'teacher_id')) {
                $table->dropConstrainedForeignId('teacher_id');
            }

            if (! Schema::hasColumn('classrooms', 'user_id')) {
                $table->foreignId('user_id')->after('name')->constrained();
            }
        });

        Schema::table('guardian_student', function (Blueprint $table) {
            if (Schema::hasColumn('guardian_student', 'guardian_id')) {
                $table->dropConstrainedForeignId('guardian_id');
            }

            if (! Schema::hasColumn('guardian_student', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('classrooms', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (! Schema::hasColumn('classrooms', 'teacher_id')) {
                $table->foreignId('teacher_id')->constrained();
            }
        });

        Schema::table('guardian_student', function (Blueprint $table) {
            if (Schema::hasColumn('guardian_student', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (! Schema::hasColumn('guardian_student', 'guardian_id')) {
                $table->foreignId('guardian_id')->constrained();
            }
        });
    }
};
