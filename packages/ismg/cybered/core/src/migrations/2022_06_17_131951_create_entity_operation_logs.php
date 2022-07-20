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
        Schema::create('entity_operation_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('entity_id');
            $table->string('entity_type',255);
            $table->string('action_taken',255);
            $table->longText('request_params');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_operation_logs');
    }
};
