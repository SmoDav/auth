<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_2fa')->default(false);
            $table->string('auth_2fa')->nullable()->unique();
            $table->string('last_auth_2fa')->nullable();
            $table->boolean('completed_login')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_2fa');
            $table->dropColumn('auth_2fa');
            $table->dropColumn('last_auth_2fa');
            $table->dropColumn('completed_login');
        });
    }
}
