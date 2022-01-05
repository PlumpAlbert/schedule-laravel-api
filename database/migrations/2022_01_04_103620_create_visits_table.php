<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    public static $tableName = 'visits';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CreateVisitsTable::$tableName, function (Blueprint $table) {
            $table->primary(['group_id', 'subject_id']);
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('group_id')->references('id')->on(CreateGroupsTable::$tableName)->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on(CreateSubjectsTable::$tableName)->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CreateVisitsTable::$tableName);
    }
}
