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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid',24)->nullable(false)->unique();
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

        Schema::create('user_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_user');
            $table->unsignedInteger('id_branch');
            $table->unsignedInteger('id_agent')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_branch')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_agent')->references('id')->on('agent')->onUpdate('cascade')->nullOnDelete();

            $table->index(['id_user','id_branch'],'idx_d_branches');
            $table->index(['id_user','id_agent'],'idx_user_agent');
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

        Schema::create('settings_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_user');
            $table->string('name',80)->nullable(false);
            $table->string('group',80)->nullable(false);
            $table->text('value')->nullable();
            $table->text('additional')->nullable();
            $table->tinyInteger('can_deleted')->default(1);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->index(['group', 'name', 'value'],'idx_settings_usr');
            $table->unique(['group', 'name'],'idx_unique_settings_usr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
