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
        Schema::create('leave_application_date', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->date('date');
            $table->enum('duration',[0,1,2]);

            $table->foreign('application_id')->references('id')->on('leave_application')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_application_date');
    }
};
