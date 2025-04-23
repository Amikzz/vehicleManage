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
        Schema::create('maintainances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->date('date');
            $table->string('description');
            $table->decimal('cost', 8, 2);
            $table->integer('mileage')->nullable();
            $table->string('done_by');

            // Foreign key constraint
            $table->foreign('car_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintainances');
    }
};
