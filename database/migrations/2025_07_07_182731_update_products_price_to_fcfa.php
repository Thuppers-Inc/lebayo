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
        Schema::table('products', function (Blueprint $table) {
            // Changer les colonnes price et old_price en integer pour les FCFA
            $table->unsignedInteger('price')->change();
            $table->unsignedInteger('old_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revenir aux décimaux si nécessaire
            $table->decimal('price', 10, 2)->change();
            $table->decimal('old_price', 10, 2)->nullable()->change();
        });
    }
};
