<?php

use App\Models\Motivo;
use App\Models\Registro;
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
        Schema::create('pausas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Registro::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->time('inicio');
            $table->time('fin')->nullable();
            $table->time('total')->nullable();
            $table->foreignIdFor(Motivo::class)->nullable()->constrained()->onUpdate('set null')->onDelete('cascade');
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
        Schema::dropIfExists('pausas');
    }
};
