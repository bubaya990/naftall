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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->string('num_R')->unique();
            $table->date('date_R');
            $table->string('definition', 60);
            $table->string('message');
            $table->enum('state', ['nouvelle', 'en_cours', 'traitée'])->default('nouvelle');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('handler_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamp('handled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->index('state');
            $table->index('handler_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
 