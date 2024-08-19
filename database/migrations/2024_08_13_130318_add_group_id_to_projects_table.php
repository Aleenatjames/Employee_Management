<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->after('id');

            // Add a foreign key constraint if the `project_groups` table exists
            $table->foreign('group_id')->references('id')->on('project_groups')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
            $table->dropForeign(['group_id']);
            
            $table->dropColumn('group_id');
        });
    }
};
