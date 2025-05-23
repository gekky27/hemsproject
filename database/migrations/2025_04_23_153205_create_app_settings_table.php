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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('slogan');
            $table->string('deskripsi');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('instagram');
            $table->string('logo')->default('HEMSLogo.png');
            $table->string('favicon')->default('favicon.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
