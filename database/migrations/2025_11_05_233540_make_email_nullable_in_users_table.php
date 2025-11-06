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
        // Supprimer l'index unique existant s'il existe
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Modifier la colonne pour la rendre nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        // Recréer l'index unique (permet plusieurs NULL mais pas de doublons pour les valeurs non-null)
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer l'index unique
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Remettre l'email comme obligatoire
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });

        // Recréer l'index unique
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email');
        });
    }
};

