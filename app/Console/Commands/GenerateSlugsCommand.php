<?php

namespace App\Console\Commands;

use App\Models\Commerce;
use App\Models\CommerceType;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère les slugs pour les commerce_types et commerces existants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Génération des slugs...');

        // Générer les slugs pour les types de commerce
        $this->info('Génération des slugs pour les types de commerce...');
        $commerceTypes = CommerceType::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($commerceTypes as $type) {
            $baseSlug = Str::slug($type->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Vérifier l'unicité
            while (CommerceType::where('slug', $slug)->where('id', '!=', $type->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $type->slug = $slug;
            $type->save();
            $this->line("  ✓ {$type->name} → {$slug}");
        }

        // Générer les slugs pour les commerces
        $this->info('Génération des slugs pour les commerces...');
        $commerces = Commerce::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($commerces as $commerce) {
            $baseSlug = Str::slug($commerce->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Vérifier l'unicité
            while (Commerce::where('slug', $slug)->where('id', '!=', $commerce->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $commerce->slug = $slug;
            $commerce->save();
            $this->line("  ✓ {$commerce->name} → {$slug}");
        }

        $this->info('✓ Génération des slugs terminée !');
        return 0;
    }
}
