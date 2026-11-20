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
        Schema::create('forecast_fetches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('provider');
            $table->date('issued_on');
            $table->string('status');
            $table->text('error')->nullable();
            $table->timestamp('fetched_at');
            $table->timestamps();

            $table->unique(['location_id', 'provider', 'issued_on']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecast_fetches');
    }
};
