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
        Schema::create('entity_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_plan_id');
            $table->integer('entity_id');
            $table->enum('entity_type', ['Course', 'LearningPath']);
            $table->tinyInteger('activated');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payment_plan_id')->references('id')->on('payment_plans')->onDelete('cascade');
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
