<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('add_on_reservation', function (Blueprint $table) {
            $table->foreignId('add_on_id')->constrained();
            $table->foreignId('reservation_id')->constrained();
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->primary(['reservation_id', 'add_on_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('add_on_reservation');
    }
};
