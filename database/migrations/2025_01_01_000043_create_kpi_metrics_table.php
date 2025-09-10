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
        Schema::create('kpi_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name');
            $table->decimal('value', 10, 2)->nullable();
            $table->string('unit', 50)->nullable();
            $table->timestamp('timestamp')->useCurrent()->unique();
            $table->json('context')->nullable();
            $table->string('calculated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_metrics');
    }
};



