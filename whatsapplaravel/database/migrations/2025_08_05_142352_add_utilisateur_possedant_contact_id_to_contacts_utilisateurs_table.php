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
        Schema::table('contacts_utilisateurs', function (Blueprint $table) {
            $table->unsignedBigInteger('utilisateurPossedantContact_id');
            $table->foreign('utilisateurPossedantContact_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts_utilisateurs', function (Blueprint $table) {
            $table->dropForeign(['utilisateurPossedantContact_id']);
            $table->dropColumn('utilisateurPossedantContact_id');
        });
    }
};
