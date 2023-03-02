<?php

use App\Models\Centro;
use App\Models\Jornada;
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
        Schema::create('calendario_centros', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->foreignIdFor(Jornada::class)->constrained();
            $table->foreignIdFor(Centro::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendario_centros');
    }
};
