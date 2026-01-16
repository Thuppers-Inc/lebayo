<?php

namespace App\Console\Commands;

use App\Models\Commerce;
use Illuminate\Console\Command;

class InitializeCommerceHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commerce:init-hours 
                            {--open=06:00 : Heure d\'ouverture (format HH:mm)}
                            {--close=19:00 : Heure de fermeture (format HH:mm)}
                            {--force : Forcer la mise à jour même si des horaires existent déjà}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialise les horaires d\'ouverture de tous les commerces (06:00-19:00 par défaut pour tous les jours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $openTime = $this->option('open');
        $closeTime = $this->option('close');
        $force = $this->option('force');

        // Valider le format des heures
        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $openTime)) {
            $this->error("Format d'heure d'ouverture invalide. Utilisez le format HH:mm (ex: 06:00)");
            return 1;
        }

        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $closeTime)) {
            $this->error("Format d'heure de fermeture invalide. Utilisez le format HH:mm (ex: 19:00)");
            return 1;
        }

        $this->info("Initialisation des horaires d'ouverture...");
        $this->info("Horaires: {$openTime} - {$closeTime} pour tous les jours");
        $this->newLine();

        // Définir les horaires pour tous les jours de la semaine
        $openingHours = [
            'monday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'tuesday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'wednesday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'thursday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'friday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'saturday' => [
                'open' => $openTime,
                'close' => $closeTime
            ],
            'sunday' => [
                'open' => $openTime,
                'close' => $closeTime
            ]
        ];

        // Récupérer tous les commerces
        $commerces = Commerce::all();
        $total = $commerces->count();
        $updated = 0;
        $skipped = 0;

        if ($total === 0) {
            $this->warn('Aucun commerce trouvé dans la base de données.');
            return 0;
        }

        $this->info("Nombre de commerces à traiter: {$total}");
        $this->newLine();

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($commerces as $commerce) {
            // Si --force n'est pas utilisé et que le commerce a déjà des horaires, on skip
            if (!$force && !empty($commerce->opening_hours) && is_array($commerce->opening_hours)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Mettre à jour les horaires
            $commerce->opening_hours = $openingHours;
            $commerce->save();

            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Afficher le résumé
        $this->info("✅ Opération terminée !");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['Commerces mis à jour', $updated],
                ['Commerces ignorés (déjà configurés)', $skipped],
                ['Total', $total]
            ]
        );

        if ($skipped > 0 && !$force) {
            $this->newLine();
            $this->comment("💡 Astuce: Utilisez --force pour forcer la mise à jour de tous les commerces, même ceux qui ont déjà des horaires.");
        }

        return 0;
    }
}
