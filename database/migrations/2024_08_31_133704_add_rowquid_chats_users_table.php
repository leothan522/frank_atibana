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
        Schema::table('chats_users', function (Blueprint $table) {
            $table->text('rowquid')->nullable()->after('mensajes_vistos');
        });

        $chatsUsers = \App\Models\ChatUser::all();
        foreach ($chatsUsers as $chat){
            $row = \App\Models\ChatUser::find($chat->id);
            $row->rowquid = generarStringAleatorio(16);
            $row->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats_users', function (Blueprint $table) {
            //
        });
    }
};
