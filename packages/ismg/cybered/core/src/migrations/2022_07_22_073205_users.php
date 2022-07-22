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
            $table->id();
            $table->text('okta_id');
            $table->string('first_name',255);
            $table->string('last_name',255);
            $table->string('email',100)->unique();
            $table->string('mobile_number',12)->unique();
            $table->string('profile_image_name',255)->nullable();
            $table->text('profile_description')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('designation_id');
            $table->tinyInteger('verified')->nullable();
            $table->tinyInteger('activated')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
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
    }
};
