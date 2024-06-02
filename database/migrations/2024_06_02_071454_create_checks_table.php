<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecksTable extends Migration
{
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('checks');
    }
}
