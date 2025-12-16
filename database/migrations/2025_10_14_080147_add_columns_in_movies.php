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
        Schema::table('movies', function (Blueprint $table) {
            if (!Schema::hasColumns('movies', ['poster_name', 'poster_uuid'])) {
                $table->string('poster_name')->nullable()->after('genre');
                $table->string('poster_uuid')->nullable()->after('poster_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['poster_name', 'poster_uuid']);
        });
    }
};
