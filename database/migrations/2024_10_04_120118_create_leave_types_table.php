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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->double('is_payable')->default(1);
            $table->double('is_carry_over')->default(0);
            $table->enum('incremental_type',['mon','qua','y','h'])->default('y');
            $table->enum('application_timing',['before','after','any'])->default('any');
            $table->unsignedBigInteger('applicable_division')->nullable();
            $table->timestamps();

            $table->foreign('applicable_division')->references('id')->on('division_parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
