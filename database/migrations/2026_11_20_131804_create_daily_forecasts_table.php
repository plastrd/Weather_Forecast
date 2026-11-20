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
        Schema::create('daily_forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forecast_fetch_id')->constrained()->onDelete('cascade');
            $table->date('forecast_date');
            $table->decimal('temp_min_c', 5, 2)->nullable();
            $table->decimal('temp_max_c', 5, 2)->nullable();
            $table->decimal('precipitation_mm', 6, 2)->nullable();
            $table->timestamps();

            $table->unique(['forecast_fetch_id', 'forecast_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_forecasts');
    }
};
