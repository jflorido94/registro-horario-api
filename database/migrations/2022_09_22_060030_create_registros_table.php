<?php

use App\Models\Usuario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Usuario::class)->constrained();
            $table->date('dia');
            $table->time('entrada')->nullable();
            $table->time('salida')->nullable();
            $table->time('total')->nullable();
            $table->boolean('is_real')->default(false);
            $table->string('comentario')->nullable();
            $table->timestamps();

            $table->unique(['usuario_id', 'dia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
};
