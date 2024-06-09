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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('rif')->unique();
            $table->string('nombre');
            $table->text('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('moneda')->default('Bs.');
            $table->string('supervisor')->nullable();
            $table->integer('default')->default(0);
            $table->text('imagen')->nullable();
            $table->text('mini')->nullable();
            $table->text('detail')->nullable();
            $table->text('cart')->nullable();
            $table->text('banner')->nullable();
            $table->text('permisos')->nullable();
            $table->timestamps();
        });

        DB::table("empresas")
            ->insert([
                "rif" => "J-50012154-7",
                "nombre" => "ATIBANA C.A.",
                "direccion" => "CARACAS, EL ROSAL, QTA. ATIBANA I",
                "telefono" => "(0212) 000.00.00",
                "email" => "admin@atibana.com",
                "supervisor" => ".",
                "default" => 1,
                "created_at" => \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
