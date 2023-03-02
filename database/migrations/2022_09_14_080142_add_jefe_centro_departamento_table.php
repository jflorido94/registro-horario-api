<?php

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
        Schema::table('centro_departamento', function (Blueprint $table) {
            $table->foreignIdFor(Usuario::class)->nullable()->after('departamento_id')->constrained();        // Jefe Departamento
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('centro_departamento', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Usuario::class);
        });
    }
};
