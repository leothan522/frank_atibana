<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->text('rowquid')->nullable()->after('permisos');
        });

        $empresas = \App\Models\Empresa::all();
        foreach ($empresas as $empresa){
            $row = \App\Models\Empresa::find($empresa->id);
            $row->rowquid = generarStringAleatorio(16);
            $row->save();
        }


    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            //
        });
    }
};
