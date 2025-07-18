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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Prix au moment de la commande
            $table->string('product_name')->nullable(); // Snapshot du nom du produit
            $table->string('product_image')->nullable(); // Snapshot de l'image du produit
            $table->text('product_description')->nullable(); // Snapshot de la description
            $table->text('notes')->nullable(); // Notes spéciales pour cet article
            $table->timestamps();

            // Index pour améliorer les performances
            $table->index(['order_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
}; 