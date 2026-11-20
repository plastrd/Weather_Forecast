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
        Schema::create('hourly_forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forecast_fetch_id')->constrained()->onDelete('cascade');
            $table->timestamp('forecast_time');
            $table->decimal('temperature_c', 5, 2)->nullable();
            $table->decimal('precipitation_mm', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['forecast_fetch_id', 'forecast_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_forecasts');
    }
};
