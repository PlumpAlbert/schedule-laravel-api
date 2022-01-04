<?php

use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public static $tableName = "users";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(CreateUsersTable::$tableName, function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name');
            $table->string('user_login')->unique();
            $table->string('user_password', 256);
            $table->unsignedSmallInteger('user_type');
            $table->unsignedBigInteger('group_id');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('group_id')->references('group_id')->on(CreateGroupsTable::$tableName);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(CreateUsersTable::$tableName);
    }
}
