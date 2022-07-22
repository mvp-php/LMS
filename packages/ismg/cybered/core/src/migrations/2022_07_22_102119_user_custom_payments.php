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
        Schema::create('user_custom_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('entity_id');
            $table->enum('entity_type',['PaymentPlan','Course','LearningPath']);
            $table->float('price',8,2);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_till')->nullable();
            $table->longText('payment_url');
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
        Schema::dropIfExists('user_custom_payments');
    }
};
