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
        Schema::create('division_leave_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_type');
            $table->unsignedBigInteger('child_id');
            $table->double('incremental_count',8,2);
            $table->timestamps();

            $table->foreign('leave_type')->references('id')->on('leave_types')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('division_children')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_leave_types');
    }
};
