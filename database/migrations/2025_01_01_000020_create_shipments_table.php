<?php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration

{



    public function up(): void

    {

        Schema::create('shipments', function (Blueprint $table) {

            $table->id();
            $table->foreignId('freight_id')->constrained()->onDelete('cascade');
            $table->foreignId('load_owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_bid_id')->nullable()->constrained('bids')->onDelete('set null');
            $table->date('pickup_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamp('actual_pickup_date')->nullable();
            $table->timestamp('actual_delivery_date')->nullable();
            $table->enum('status', ['pending_pickup', 'in_transit', 'delivered', 'cancelled', 'delayed', 'exception', 'on_hold', 'ready_for_pickup'])->default('pending_pickup');
            $table->string('tracking_number')->nullable();
            $table->geometry('origin_coord')->nullable();
            $table->geometry('dest_coord')->nullable();
            $table->geometry('current_location_coord')->nullable();
            $table->string('current_location_name')->nullable();
            $table->timestamp('estimated_eta')->nullable();
            $table->timestamps();
        });
    }





    public function down(): void

    {

        Schema::dropIfExists('shipments');
    }
};
