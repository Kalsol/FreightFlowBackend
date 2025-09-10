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
        Schema::create('rate_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('transporter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('rate_type');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->json('pricing_tiers')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_sheets');
    }
};



