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
        Schema::create('tripays', function (Blueprint $table) {
            $table->id();
            $table->string('tripay_mode');
            $table->string('tripay_merchant');
            $table->string('tripay_api');
            $table->string('tripay_private');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tripays');
    }
};
