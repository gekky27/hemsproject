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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizers_id');
            $table->foreign('organizers_id')->references('id')->on('organizers')->onDelete('cascade');
            $table->unsignedBigInteger('venues_id');
            $table->foreign('venues_id')->references('id')->on('venues')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description');
            $table->date('event_date');
            $table->time('event_time');
            $table->integer('ticket_price');
            $table->integer('available_seats')->default(0);
            $table->string('cover_image')->default('default.jpg');
            $table->enum('status', ['ready', 'soldout'])->default('ready');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
