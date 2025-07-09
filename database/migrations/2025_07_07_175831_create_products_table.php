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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commerce_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable(); // Prix barré
            $table->string('image')->nullable();
            $table->json('gallery')->nullable(); // Galerie d'images
            $table->string('sku')->nullable(); // Code produit
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false); // Produit mis en avant
            $table->json('specifications')->nullable(); // Caractéristiques du produit
            $table->json('tags')->nullable(); // Tags/mots-clés
            $table->decimal('weight', 8, 2)->nullable(); // Poids en kg
            $table->string('unit')->default('pièce'); // unité (pièce, kg, litre, etc.)
            $table->integer('preparation_time')->nullable(); // Temps de préparation en minutes
            $table->timestamps();
            
            // Index pour les performances
            $table->index(['commerce_id', 'is_available']);
            $table->index(['category_id']);
            $table->index(['is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
