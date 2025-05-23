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
        Schema::create('event_seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('venue_seat_id');
            $table->foreign('venue_seat_id')->references('id')->on('venue_seats')->onDelete('cascade');
            $table->enum('status', ['available', 'unavailable', 'booked', 'sold'])->default('available');
            $table->timestamps();
            $table->index(['event_id', 'venue_seat_id']);
            $table->index(['venue_seat_id', 'status']);
            $table->unique(['event_id', 'venue_seat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_seats');
    }
};
