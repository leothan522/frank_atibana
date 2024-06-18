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
        Schema::create('planificaciones_detalles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('planificaciones_id')->unsigned();
            $table->date('fecha');
            $table->bigInteger('recetas_id')->unsigned();
            $table->decimal('cantidad', 12, 3);
            $table->integer('renglon')->nullable();
            $table->foreign('planificaciones_id')->references('id')->on('planificaciones')->cascadeOnDelete();
            $table->foreign('recetas_id')->references('id')->on('recetas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificaciones_detalles');
    }
};
