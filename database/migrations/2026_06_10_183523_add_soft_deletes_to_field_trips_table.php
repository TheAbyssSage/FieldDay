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
        // up functie voegt soft delete aan de field_trips, zodat we trips kunnen verwijderen zonder ze echt uit de database te verwijderen, we kunnen ze later nog herstellen als dat nodig is.
        Schema::table('field_trips', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // down functie verwijdert de soft delete van de field_trips, als we deze migratie terugdraaien, we kunnen dan niet meer gebruik maken van soft deletes voor trips.
        Schema::table('field_trips', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
// soft deletes() voegt een nullable deleted_at column toe; dropSoftDeletes() verwijdert deze column weer.