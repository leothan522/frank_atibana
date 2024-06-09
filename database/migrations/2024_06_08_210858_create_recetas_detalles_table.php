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
        Schema::create('recetas_detalles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('recetas_id')->unsigned();
            $table->bigInteger('articulos_id')->unsigned();
            $table->bigInteger('unidades_id')->unsigned();
            $table->decimal('cantidad', 12, 3);
            $table->integer('renglon')->nullable();
            $table->foreign('recetas_id')->references('id')->on('recetas')->cascadeOnDelete();
            $table->foreign('articulos_id')->references('id')->on('articulos')->cascadeOnDelete();
            $table->foreign('unidades_id')->references('id')->on('unidades')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas_detalles');
    }
};
