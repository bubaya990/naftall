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
        Schema::create('rams', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Ensures InnoDB engine for foreign key support

            $table->id(); // Auto-incrementing primary key

            $table->string('capacity'); // RAM capacity (e.g., '4GB', '8GB')
            $table->enum('state', ['good', 'bad', 'out of order']); // RAM state

            $table->unsignedBigInteger('computer_id'); // Foreign key to computers table

            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rams'); // Drops the rams table
    }
};
