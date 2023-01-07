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
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_city')->nullable(false);
            $table->string('name',50)->nullable(false);
            $table->text('address')->nullable();
            $table->string('email',120)->nullable();
            $table->string('phone',14)->nullable();
            $table->tinyInteger('type_branch')->default(1);
            $table->string('latitude',255)->nullable();
            $table->string('longitude',255)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();

            $table->foreign('id_city')->references('id')->on('cities')->onUpdate('cascade')->onDelete('restrict');
        });
        Schema::create('agent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nik',50)->nullable(false);
            $table->text('address')->nullable();
            $table->string('whatsapp',14)->nullable();
            $table->text('foto_ktp')->nullable();
            $table->timestamps();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('roles_name',100)->nullable(false);
            $table->index('roles_name','idx_roles');
        });
        Schema::create('corporate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',155)->nullable(false);
            $table->string('pic',45)->nullable();
            $table->string('pic_email',160)->nullable();
            $table->string('pic_phone',14)->nullable();
            $table->timestamps();

            $table->index('name','idx_corporate_name');
            $table->index(['pic','pic_email','pic_phone'],'idx_pic');
        });
        Schema::create('vendor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_name',145)->nullable();
            $table->string('vendor_company',160)->nullable();
            $table->string('email',160)->nullable();
            $table->string('phone',14)->nullable();
            $table->string('npwp',45)->nullable();
            $table->text('tag')->nullable();
            $table->timestamps();

            $table->index(['email','phone'],'idx_vendor_contact');
            $table->index(['vendor_name','vendor_company','pic_phone'],'idx_identity_vendor');
            $table->fullText('tag','idx_tag_vendor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
        Schema::dropIfExists('agent');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('corporate');
        Schema::dropIfExists('vendor');
    }
};
