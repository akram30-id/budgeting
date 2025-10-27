<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TreasuryDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('treasuries');


        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->string('month', 16)->index()->nullable();
            $table->string('year', 4)->index()->nullable();
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });


        Schema::create('treasury_detail', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_detail_no', 50)->index()->nullable();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->string('treasury_detail_name', 100)->nullable();
            $table->string('notes', 100)->nullable();
            $table->bigInteger('income_value')->nullable();
            $table->bigInteger('expense_value')->nullable();
            $table->integer('is_checked')->index()->nullable()->default(0);
            $table->integer('is_debt')->index()->nullable()->default(0);
            $table->string('user_id', 50)->index()->nullable();
            $table->integer('state')->index()->nullable()->default(1);
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
        Schema::dropIfExists('treasury_detail');

        Schema::dropIfExists('treasuries');

        Schema::create('treasuries', function (Blueprint $table) {
            $table->id();
            $table->string('project_no', 50)->index()->nullable();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->string('treasury_title', 100)->index()->nullable();
            $table->string('treasury_description')->index()->nullable();
            $table->string('month', 16)->index()->nullable();
            $table->string('year', 4)->index()->nullable();
            $table->bigInteger('income_value')->nullable()->default(0);
            $table->bigInteger('expenses_value')->nullable()->default(0);
            $table->integer('is_checked')->index()->nullable()->default(0);
            $table->integer('is_debt')->index()->nullable()->default(0);
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });
    }
}
