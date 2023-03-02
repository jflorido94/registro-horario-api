<?php

use App\Models\Centro;
use App\Models\Departamento;
use App\Models\Usuario;
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
        Schema::create('centro_departamento', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Centro::class)->constrained();
            $table->foreignIdFor(Departamento::class)->constrained();
            $table->timestamps();

            $table->unique(['centro_id', 'departamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centro_departamento');
    }
};
