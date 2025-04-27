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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade'); 
            $table->string('name'); // "carburant" ou "commercial"        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
    
        // Supprimez d'abord les tables dépendantes si nécessaire
        Schema::dropIfExists('sites'); // Exemple
        
        Schema::dropIfExists('branches');
        
        Schema::enableForeignKeyConstraints();
    }
};
   