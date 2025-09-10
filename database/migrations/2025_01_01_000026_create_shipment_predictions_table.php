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
        Schema::create('shipment_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->unique()->constrained()->onDelete('cascade');
            $table->timestamp('predicted_eta')->nullable();
            $table->decimal('confidence_level', 5, 2)->nullable();
            $table->string('model_version')->nullable();
            $table->json('features')->nullable();
            $table->string('prediction_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_predictions');
    }
};



