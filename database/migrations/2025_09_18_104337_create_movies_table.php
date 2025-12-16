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
      Schema::create('movies', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('genre');
    $table->string('poster')->nullable( );
    $table->text('description')->nullable();
    $table->date('release_date')->nullable( );
    $table->integer('duration')->nullable(); 
    $table->boolean('is_active')->default(true);// duration in minutes
    $table->timestamps();
});

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
