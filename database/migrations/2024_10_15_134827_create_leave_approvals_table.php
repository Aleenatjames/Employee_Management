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
        Schema::create('leave_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->date('date');
            $table->unsignedBigInteger('approver_id');
            $table->enum('status',['pending','rejected','approved']);
            $table->text('comment')->nullable();
            $table->integer('level');

            $table->foreign('application_id')->references('id')->on('leave_application')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_approvals');
    }
};
