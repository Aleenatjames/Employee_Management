<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\on;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->boolean('entry_type');
            $table->time('entry_time');
            $table->double('duration')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendance')->onDelete('cascade');
            $table->foreign('modified_by')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_entries');
    }
};
