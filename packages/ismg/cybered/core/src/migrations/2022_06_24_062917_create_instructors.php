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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('role_id');
            $table->integer('approved_by');
            $table->string('instructor_name',255);
            $table->longText('profile_description')->nullable();
            $table->string('profile_image_name',255)->nullable();
            $table->float('average_rating',10,2)->nullable();
            $table->date('valid_from');
            $table->date('valid_till');
            $table->tinyInteger('activated')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructors');
    }
};
