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
        Schema::create('errand_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('pickup_address');
            $table->text('delivery_address');
            $table->decimal('estimated_cost', 10, 2)->default(0);
            $table->enum('urgency_level', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('contact_phone')->nullable();
            $table->datetime('preferred_delivery_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errand_requests');
    }
};
