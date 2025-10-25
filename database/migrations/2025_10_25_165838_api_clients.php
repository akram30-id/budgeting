<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('api_code', 8)->index()->nullable();
            $table->string('api_key', 200)->index()->nullable();
            $table->string('description')->nullable();
            $table->integer('api_level')->index()->nullable()->comment('#1=X-API-KEY | #2=access_token');
            $table->string('access_token')->index()->nullable();
            $table->dateTime('token_expired_at')->nullable();
            $table->json('ip_whitelist');
            $table->integer('state')->index()->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_clients');
    }
}
