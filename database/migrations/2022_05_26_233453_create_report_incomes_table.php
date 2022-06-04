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
        Schema::create('report_incomes', function (Blueprint $table) {
            $table->id();
            // foreign key
            $table->integer('report_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->integer('income_id')->nullable();

            $table->double('amount')->nullable();

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
        Schema::dropIfExists('report_incomes');
    }
};
