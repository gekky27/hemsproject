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
        Schema::create('venue_seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venues_id');
            $table->foreign('venues_id')->references('id')->on('venues')->onDelete('cascade');
            $table->string('row_name');
            $table->integer('seat_number');
            $table->decimal('x_coordinate', 10, 2);
            $table->decimal('y_coordinate', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_seats');
    }
};
