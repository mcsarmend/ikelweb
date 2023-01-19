<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatCpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_cps', function (Blueprint $table) {
            $table->id("idcp");
            $table->bigInteger("idmunicipio");
            $table->bigInteger("idestado");
            $table->bigInteger("cp");
            $table->string("colonia");
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
        Schema::dropIfExists('cat_cps');
    }
}
