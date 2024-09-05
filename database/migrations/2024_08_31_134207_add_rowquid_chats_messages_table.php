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
        Schema::table('chats_messages', function (Blueprint $table) {
            $table->text('rowquid')->nullable()->after('message');
        });

        $chatMessages = \App\Models\ChatMessage::all();
        foreach ($chatMessages as $chatMessage){
            $row = \App\Models\ChatMessage::find($chatMessage->id);
            $row->rowquid = generarStringAleatorio(16);
            $row->save();
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats_messages', function (Blueprint $table) {
            //
        });
    }
};
