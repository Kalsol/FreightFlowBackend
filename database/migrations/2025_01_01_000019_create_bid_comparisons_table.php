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
        Schema::create('bid_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_id')->constrained()->onDelete('cascade');
            $table->foreignId('bid_id_1')->constrained('bids')->onDelete('cascade');
            $table->foreignId('bid_id_2')->constrained('bids')->onDelete('cascade');
            $table->json('comparison_metrics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_comparisons');
    }
};



