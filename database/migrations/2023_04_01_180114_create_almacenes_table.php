<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('empresas_id')->unsigned();
            $table->string('codigo');
            $table->string('nombre');
            $table->integer('tipo')->default(0);
            $table->foreign('empresas_id')->references('id')->on('empresas')->cascadeOnDelete();
            $table->timestamps();
        });

        /*DB::table("almacenes")
            ->insert([
                "empresas_id" => 1,
                "codigo" => "ALMP",
                "nombre" => "Almacén Principal",
                "tipo" => 1,
                "created_at" => \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacenes');
    }
};
