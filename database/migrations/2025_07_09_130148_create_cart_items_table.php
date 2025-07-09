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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Prix au moment de l'ajout au panier
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['cart_id']);
            $table->index(['product_id']);
            
            // Un produit ne peut être qu'une seule fois dans un panier (la quantité gère les multiples)
            $table->unique(['cart_id', 'product_id'], 'unique_cart_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
