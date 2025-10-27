<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTreasuryMemberId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropColumns('treasury_members', 'member_email');

        Schema::table('treasury_members', function (Blueprint $table) {
            $table->unsignedBigInteger('member_id')->index()->nullable()->after('treasury_no');
            $table->unsignedBigInteger('owner_id')->index()->nullable()->after('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treasury_members', function (Blueprint $table) {
            $table->string('member_email', 100)->index()->nullable()->after('treasury_no');
        });

        Schema::dropColumns('treasury_members', ['member_id', 'owner_id']);
    }
}
