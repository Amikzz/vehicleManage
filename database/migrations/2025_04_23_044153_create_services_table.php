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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('service_type')->nullable();
            $table->date('service_date')->nullable();
            $table->string('service_location')->nullable();
            $table->float('service_cost')->nullable();
            $table->integer('mileage')->nullable();
            $table->integer('next_service_mileage')->nullable();
            $table->date('next_service_date')->nullable();
            $table->string('service_notes')->nullable();
            $table->string('done_by')->nullable();
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
