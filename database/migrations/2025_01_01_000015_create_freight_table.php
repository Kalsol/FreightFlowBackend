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
        Schema::create('freights', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid', 36)->unique()->nullable();
            $table->bigInteger('load_owner_id');
            $table->string('origin_location');
            $table->string('destination_location');
            $table->geometry('origin_coord')->nullable();
            $table->geometry('dest_coord')->nullable();
            $table->date('pickup_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('weight_unit')->default('kg');
            $table->string('dimensions')->nullable();
            $table->string('dimension_unit')->default('cm');
            $table->string('freight_type')->nullable();
            $table->text('description')->nullable();
            $table->decimal('desired_price', 10, 2)->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('bid_deadline')->nullable();
            $table->enum('status', ['open', 'bidding_closed', 'assigned', 'in_transit', 'delivered', 'cancelled', 'draft'])->default('open');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->json('required_equipment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freights');
    }
};
