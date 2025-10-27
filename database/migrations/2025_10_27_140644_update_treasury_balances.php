<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTreasuryBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('treasury_estimate_balances');
        Schema::dropIfExists('treasury_actual_balances');

        Schema::create('treasury_estimate_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_detail_no', 50)->index()->nullable();
            $table->bigInteger('estimate_value')->nullable();
            $table->timestamps();
        });

        Schema::create('treasury_actual_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_detail_no', 50)->index()->nullable();
            $table->bigInteger('actual_value')->nullable();
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

        Schema::dropIfExists('treasury_estimate_balances');
        Schema::dropIfExists('treasury_actual_balances');

        Schema::create('treasury_estimate_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->bigInteger('value')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::create('treasury_actual_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->bigInteger('value')->nullable()->default(0);
            $table->timestamps();
        });
    }
}
