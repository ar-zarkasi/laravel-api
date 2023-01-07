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
        Schema::create('settings_app', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80)->nullable(false);
            $table->string('group',80)->nullable(false);
            $table->text('value')->nullable();
            $table->text('additional')->nullable();
            $table->tinyInteger('can_deleted')->default(1);
            $table->timestamps();

            $table->index(['group', 'name', 'value'],'idx_settings');
            $table->unique(['group', 'name'],'idx_unique_settings');
        });
        Schema::create('country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',120)->nullable(false);
            $table->string('continent',60)->nullable(false);
            $table->string('region',120)->nullable();
            $table->char('country_code',3)->nullable();
            $table->char('code',2)->nullable();
            $table->char('phone_code',3)->nullable();

            $table->index(['country_code', 'code', 'phone_code'],'idx_country');
            $table->index('name','idx_negara');
        });
        Schema::create('cities', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_country')->nullable(false);
            $table->string('city', 145)->nullable(false);
            $table->string('district', 120)->nullable();
            $table->char('citycode',4)->nullable();
            $table->char('airport_code',4)->nullable();
            $table->string('airport_name',60)->nullable();

            $table->foreign('id_country')->references('id')->on('country')->onUpdate('cascade')->onDelete('restrict');

            $table->index(['id_country', 'city'],'idx_city_identity');
            $table->fullText('city','idx_fullname_city');
            $table->index(['city', 'airport_code', 'airport_name'],'idx_airport_city');
        });
        Schema::create('currency', function (Blueprint $table){
            $table->increments('id');
            $table->string('name',5)->nullable(false);

            $table->index('name','idx_curr_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_app');
        Schema::dropIfExists('country');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('currency');
    }
};
