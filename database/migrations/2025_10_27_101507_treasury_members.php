<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TreasuryMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treasuries', function (Blueprint $table) {
            $table->string('treasury_owner', 50)->index()->nullable()->after('treasury_no');
        });

        Schema::create('treasury_members', function (Blueprint $table) {
            $table->id();
            $table->string('treasury_no', 50)->index()->nullable();
            $table->string('member_email', 100)->index()->nullable();
            $table->integer('state')->index()->nullable()->default(1);
            $table->integer('is_accepted')->index()->nullable()->default(0);
            $table->string('invitation_code', 200)->index()->default();
            $table->integer('can_look')->index()->nullable()->default(1);
            $table->integer('can_edit')->index()->nullable()->default(0);
            $table->dateTime('invited_at')->nullable();

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
        Schema::dropColumns('treasuries', 'treasury_owner');
        Schema::dropIfExists('treasury_members');
    }
}
