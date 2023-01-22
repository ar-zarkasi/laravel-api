<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('roles_name',100)->nullable(false);
            $table->index('roles_name','idx_roles');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid',120)->nullable(false)->unique();
            $table->string('fullname',160)->nullable(false);
            $table->text('password')->nullable(false);
            $table->string('username',120)->nullable();
            $table->string('email',120)->nullable(false);
            $table->string('phone',14)->nullable(false);
            $table->string('salt',255);
            $table->unsignedInteger('id_roles');
            $table->tinyInteger('active')->default(1);
            $table->timestamps();

            $table->foreign('id_roles')->references('id')->on('roles')->onUpdate('cascade')->onDelete('restrict');
        });

        Schema::create('reset_password', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_user');
            $table->string('token_reset',45)->unique();
            $table->dateTime('expired_at')->nullable(false);
            $table->tinyInteger('status')->default(1);

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
            $table->index(['id_user','token_reset'],'idx_token_user');
            $table->index(['token_reset','status'],'idx_status_token');
            $table->index('expired_at','idx_expired_token');
        });

        Schema::create('user_auth', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_user');
            $table->string('token',160)->unique();
            $table->json('data')->nullable();
            $table->dateTime('expired')->nullable(false);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->index('expired','idx_expired_token_login');
            $table->fullText('data','idx_full_data_login');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_branches');
        Schema::dropIfExists('reset_password');
        Schema::dropIfExists('user_auth');
        Schema::dropIfExists('settings_user');
    }
};
