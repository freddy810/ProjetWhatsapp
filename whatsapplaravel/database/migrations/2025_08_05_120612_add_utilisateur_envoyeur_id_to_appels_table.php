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
        Schema::table('appels', function (Blueprint $table) {
            $table->unsignedBigInteger('utilisateurEnvoyeur_id');
            $table->foreign('utilisateurEnvoyeur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appels', function (Blueprint $table) {
            $table->dropForeign(['utilisateurEnvoyeur_id']);
            $table->dropColumn('utilisateurEnvoyeur_id');
        });
    }
};
