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
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('empresas_id')->unsigned();
            $table->string('codigo');
            $table->string('descripcion');
            $table->dateTime('fecha');
            $table->decimal('cantidad', 12, 3)->nullable();
            $table->text('auditoria')->nullable();
            $table->integer('estatus')->default(1);
            $table->foreign('empresas_id')->references('id')->on('empresas')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
