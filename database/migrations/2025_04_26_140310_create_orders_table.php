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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->string('description');
            $table->string('reference')->unique();
            $table->string('tripay_reference')->unique();
            $table->string('merchant_ref')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->string('payment_method');
            $table->string('checkout_url')->nullable();
            $table->string('pay_code')->nullable();
            $table->string('qr_image')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'canceled'])
                ->default('pending');
            $table->datetime('paid_at')->nullable();
            $table->datetime('expire_time');
            $table->integer('ticket_count');
            $table->text('order_items');
            $table->index('payment_status');
            $table->index('expire_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
