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
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('utilisateurReceveurMessage_id');
            $table->foreign('utilisateurReceveurMessage_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messagess', function (Blueprint $table) {
            $table->dropForeign(['utilisateurReceveurMessage_id']);
            $table->dropColumn('utilisateurReceveurMessage_id');
        });
    }
};
