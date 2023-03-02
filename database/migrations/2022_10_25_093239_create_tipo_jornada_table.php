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
        Schema::create('tipo_jornadas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->boolean('vacaciones');
            $table->boolean('personal');
            $table->boolean('remunerado');
            $table->boolean('libre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_jornadas');
    }
};
