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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('users')->onDelete('cascade');
            $table->string('telematics_device_id')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->integer('year')->nullable();
            $table->string('vin')->unique()->nullable();
            $table->string('capacity')->nullable();
            $table->string('emission_class')->nullable();
            $table->string('license_plate')->unique()->nullable();
            $table->string('vehicle_type')->nullable();
            $table->foreignId('current_driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};



