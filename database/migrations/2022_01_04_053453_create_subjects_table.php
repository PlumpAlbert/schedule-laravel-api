<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    public static $tableName = "subjects";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CreateSubjectsTable::$tableName, function (Blueprint $table) {
            $table->id('id');
            $table->string('audience');
            $table->unsignedSmallInteger('type');
            $table->string('name');
            $table->time('time');
            $table->unsignedSmallInteger('weekday');
            $table->unsignedSmallInteger('weektype');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();
            $table->foreign('teacher_id')->references('id')->on(CreateUsersTable::$tableName);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CreateSubjectsTable::$tableName);
    }
}
