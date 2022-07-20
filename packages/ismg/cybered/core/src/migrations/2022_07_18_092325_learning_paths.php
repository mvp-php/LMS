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
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_name',255)->nullable();
            $table->string('intro_video',255)->nullable();
            $table->float('price',6,2)->nullable();
            $table->float('average_rating',6,2)->nullable();;
            $table->tinyInteger('activated')->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['title']);
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
