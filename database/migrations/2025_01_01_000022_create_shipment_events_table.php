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
        Schema::create('shipment_events', function (Blueprint $table) {

            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('event_type');
            $table->timestamp('timestamp')->useCurrent();
            $table->json('data')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->geometry('location_coord')->nullable();
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
