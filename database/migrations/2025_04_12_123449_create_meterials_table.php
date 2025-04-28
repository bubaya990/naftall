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
         

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_number')->unique();
            $table->string('serial_number')->unique();
            $table->enum('state', ['bon', 'défectueux', 'hors_service'])->charset('utf8mb4');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('cascade');
            $table->foreignId('corridor_id')->nullable()->constrained('corridors')->onDelete('cascade');
            $table->string('materialable_type');
            $table->unsignedBigInteger('materialable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

       

        Schema::dropIfExists('materials');
    }

         
};