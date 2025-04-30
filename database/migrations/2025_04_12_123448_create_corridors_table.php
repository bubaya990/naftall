<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('corridors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer d'abord les enregistrements de `locations` avant de supprimer la table `corridors`
        DB::table('locations')->delete();

        // Supprimer la clé étrangère avant la suppression de la table `corridors`
        Schema::table('corridors', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
        });

        // Supprimer la table `corridors`
        Schema::dropIfExists('corridors');
    }
};               