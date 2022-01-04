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
            $table->id('subject_id');
            $table->string('subject_audience');
            $table->unsignedSmallInteger('subject_type');
            $table->string('subject_name');
            $table->time('subject_time');
            $table->unsignedSmallInteger('subject_weekday');
            $table->unsignedSmallInteger('subject_weektype');
            $table->unsignedBigInteger('subject_teacher');
            $table->timestamps();
            $table->foreign('subject_teacher')->references('user_id')->on(CreateUsersTable::$tableName);
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
