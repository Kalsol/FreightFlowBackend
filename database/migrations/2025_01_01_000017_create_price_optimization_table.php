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
        Schema::create('price_optimization', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('predicted_price', 10, 2)->nullable();
            $table->string('model_version')->nullable();
            $table->json('features')->nullable();
            $table->decimal('prediction_confidence', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_optimization');
    }
};



