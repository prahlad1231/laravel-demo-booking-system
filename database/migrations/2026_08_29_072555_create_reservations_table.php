<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\ReservationStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('space_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedInteger('capacity_used')->default(0);
            $table->string('status')->default(ReservationStatus::Pending->value);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('reservations');
    }
};
