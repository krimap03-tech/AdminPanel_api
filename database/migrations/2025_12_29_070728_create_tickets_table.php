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
        Schema::create('tickets', function (Blueprint $table) {
    $table->id();
    $table->string('ticket_no')->unique();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('booking_id');
    $table->string('movie');
    $table->date('date');
    $table->string('time');
    $table->string('seat');
    $table->integer('amount');
    $table->string('payment_id');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
