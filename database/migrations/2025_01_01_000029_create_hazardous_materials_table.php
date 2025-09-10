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
        Schema::create('hazardous_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('un_number');
            $table->string('proper_shipping_name')->nullable();
            $table->string('hazard_class', 50)->nullable();
            $table->string('packing_group', 50)->nullable();
            $table->string('sds_link')->nullable();
            $table->string('technical_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazardous_materials');
    }
};



