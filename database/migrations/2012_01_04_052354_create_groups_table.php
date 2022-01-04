<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    public static $tableName = "groups";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CreateGroupsTable::$tableName, function (Blueprint $table) {
            $table->id('group_id');
            $table->string('group_faculty');
            $table->string('group_specialty');
            $table->unsignedSmallInteger('group_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CreateGroupsTable::$tableName);
    }
}
