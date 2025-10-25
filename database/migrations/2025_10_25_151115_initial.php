<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('project_type_code', 50)->index()->nullable();
            $table->string('project_type_name', 50)->nullable();
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_no', 50)->index()->nullable();
            $table->string('project_type_code', 50)->index()->nullable();
            $table->string('project_name', 100)->nullable();
            $table->string('project_owner', 100)->index()->nullable()->comment('email user');
            $table->string('description', 200)->nullable();
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->id();
            $table->string('project_no', 50)->index()->nullable();
            $table->string('email', 100)->index()->nullable();
            $table->integer('access_look')->index()->nullable()->default(1);
            $table->integer('access_edit')->index()->nullable()->default(0);
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });

        Schema::table('treasuries', function (Blueprint $table) {
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

        Schema::table('treasury_estimate_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->bigInteger('value')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::table('treasury_actual_balances', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->bigInteger('value')->nullable()->default(0);
            $table->timestamps();
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->id();
            $table->string('debt_no', 50)->index()->nullable();
            $table->string('creditors', 100)->index()->nullable();
            $table->bigInteger('value')->nullable()->default(0);
            $table->integer('installment')->nullable()->default(0)->comment('pelunasan keberapa');
            $table->integer('total_installment')->nullable()->comment('jumlah tempo');
            $table->date('start_date')->nullable();
            $table->integer('state')->index()->nullable()->default(1);
            $table->timestamps();
        });

        Schema::table('debt_payments', function (Blueprint $table) {
            $table->id();
            $table->string('debt_no', 50)->index()->nullable();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->bigInteger('value')->nullable();
            $table->integer('installment')->nullable()->default(0)->comment('pelunasan keberapa');
            $table->string('photo_proof')->nullable();
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
        Schema::dropIfExists('debt_payments');
        Schema::dropIfExists('debts');
        Schema::dropIfExists('treasury_actual_balances');
        Schema::dropIfExists('treasury_estimate_balances');
        Schema::dropIfExists('treasuries');
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_types');
    }
}
