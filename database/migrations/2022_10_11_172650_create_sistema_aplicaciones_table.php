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
        Schema::create('sistema_aplicaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('idSistema');
            $table->unsignedInteger('idEquipo');
            $table->string('aplicacion');
            $table->string('ipFija');
            $table->string('puerto');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sistema_aplicaciones');
    }
};
