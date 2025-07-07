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
        Schema::table('users', function (Blueprint $table) {
            // Remplacer 'name' par 'nom' et 'prenoms'
            $table->dropColumn('name');
            $table->string('nom')->after('id');
            $table->string('prenoms')->after('nom');
            
            // Nouveaux champs personnels
            $table->date('date_naissance')->nullable()->after('prenoms');
            $table->string('lieu_naissance')->nullable()->after('date_naissance');
            $table->string('ville')->nullable()->after('lieu_naissance');
            $table->string('commune')->nullable()->after('ville');
            $table->string('photo')->nullable()->after('commune');
            
            // Nouveau champ de contact
            $table->string('indicatif', 10)->default('+225')->after('email_verified_at');
            $table->string('numero_telephone')->nullable()->after('indicatif');
            
            // Champs d'administration et rôles
            $table->enum('account_type', ['client', 'admin', 'agent'])->default('client')->after('password');
            $table->boolean('is_super_admin')->default(false)->after('account_type');
            $table->enum('role', ['user', 'moderator', 'manager', 'developer'])->default('user')->after('is_super_admin');
            
            // Documents d'identité
            $table->string('numero_cni')->nullable()->after('role');
            $table->string('numero_passeport')->nullable()->after('numero_cni');
            
            // Soft deletes
            $table->softDeletes();
            
            // Index pour améliorer les performances
            $table->index(['account_type', 'is_super_admin']);
            $table->index('role');
            $table->index('ville');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['account_type', 'is_super_admin']);
            $table->dropIndex(['role']);
            $table->dropIndex(['ville']);
            
            // Supprimer les nouveaux champs
            $table->dropColumn([
                'nom',
                'prenoms', 
                'date_naissance',
                'lieu_naissance',
                'ville',
                'commune',
                'photo',
                'indicatif',
                'numero_telephone',
                'account_type',
                'is_super_admin',
                'role',
                'numero_cni',
                'numero_passeport'
            ]);
            
            // Supprimer soft deletes
            $table->dropSoftDeletes();
            
            // Restaurer le champ 'name' original
            $table->string('name')->after('id');
        });
    }
};
