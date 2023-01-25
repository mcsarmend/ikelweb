<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->id("idaddress")->increments();;
            $table->bigInteger("iduser");
            $table->bigInteger("idmunicipio");
            $table->bigInteger("idestado");
            $table->bigInteger("cp");
            $table->string("colonia");
            $table->string("calle");
            $table->string("numero");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
