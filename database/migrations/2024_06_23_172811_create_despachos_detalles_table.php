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
        Schema::create('despachos_detalles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('despachos_id')->unsigned();
            $table->bigInteger('recetas_id')->unsigned();
            $table->bigInteger('almacenes_id')->unsigned();
            $table->decimal('cantidad', 12, 3);
            $table->integer('renglon')->nullable();
            $table->foreign('despachos_id')->references('id')->on('despachos')->cascadeOnDelete();
            $table->foreign('recetas_id')->references('id')->on('recetas')->cascadeOnDelete();
            $table->foreign('almacenes_id')->references('id')->on('almacenes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despachos_detalles');
    }
};
