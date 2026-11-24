<?php

namespace App\Http\Controllers;

use App\Models\TenantOnboardingProgress;
use App\Support\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class OnboardingController extends Controller
{
    public function index(TenantContext $tenantContext)
    {
        $tenant = $tenantContext->tenant();
        $user = auth()->user();
        $progress = TenantOnboardingProgress::query()
            ->where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->pluck('guide_key')
            ->all();

        return view('onboarding.index', [
            'companyName' => $tenant->name,
            'completedGuides' => $progress,
            'modules' => $this->modules(),
        ]);
    }

    public function example(string $module, TenantContext $tenantContext)
    {
        abort_unless(isset($this->modules()[$module]), 404);

        $tenant = $tenantContext->tenant();
        $user = auth()->user();
        $guide = $this->modules()[$module];
        $guide['key'] = 'scenario-'.$module;
        $guide['completed'] = TenantOnboardingProgress::query()
            ->where('tenant_id', $tenant->id)
            ->where('user_id', $user->id)
            ->where('guide_key', $guide['key'])
            ->whereNotNull('completed_at')
            ->exists();
        $guide['moduleUrl'] = $guide['route'] && Route::has($guide['route'])
            ? route($guide['route'])
            : null;

        return view('onboarding.example', compact('guide'));
    }

    public function complete(string $guide, TenantContext $tenantContext): JsonResponse
    {
        $allowedGuides = array_merge(
            ['welcome'],
            array_map(fn (string $module) => 'scenario-'.$module, array_keys($this->modules()))
        );
        abort_unless(in_array($guide, $allowedGuides, true), 404);

        TenantOnboardingProgress::query()->updateOrCreate(
            [
                'tenant_id' => $tenantContext->id(),
                'user_id' => auth()->id(),
                'guide_key' => $guide,
            ],
            ['completed_at' => now()]
        );

        return response()->json(['completed' => true]);
    }

    private function modules(): array
    {
        return [
            'elevage' => [
                'title' => 'Élevage et bandes',
                'icon' => 'columns',
                'description' => 'Organisez les lots, suivez les effectifs et consultez les étapes de croissance.',
                'route' => 'bande.index',
                'scenario' => 'Suivre une bande de poulets de chair',
                'steps' => [
                    'Une bande regroupe les animaux introduits ensemble dans un bâtiment, avec sa date de lancement et son effectif initial.',
                    'Les suivis de nutrition, mortalité et vaccination sont rattachés à la bande pour garder son historique.',
                    'Les indicateurs du tableau de bord se mettent à jour à partir des opérations réellement enregistrées dans votre espace.',
                ],
                'sample' => [
                    ['date' => '03/11/2026', 'operation' => 'Création de la bande « Lot démo A »', 'detail' => '500 poussins · Bâtiment Nord'],
                    ['date' => '10/11/2026', 'operation' => 'Suivi nutritionnel', 'detail' => '85 kg d’aliment consommés'],
                    ['date' => '10/11/2026', 'operation' => 'Point de mortalité', 'detail' => '3 sujets · données fictives'],
                ],
            ],
            'depenses' => [
                'title' => 'Dépenses',
                'icon' => 'dollar-sign',
                'description' => 'Classez les dépenses et gardez une trace claire des sorties d’argent.',
                'route' => 'depenses.index',
                'scenario' => 'Enregistrer un achat de provende',
                'steps' => [
                    'Une catégorie permet de regrouper les dépenses comparables, par exemple alimentation ou transport.',
                    'Une dépense indique un montant, une date et un libellé qui facilitent les recherches ultérieures.',
                    'Dans votre espace réel, l’opération suit les règles de caisse configurées pour votre entreprise.',
                ],
                'sample' => [
                    ['date' => '12/11/2026', 'operation' => 'Achat de provende', 'detail' => '5 sacs · 90 000 FCFA'],
                    ['date' => '12/11/2026', 'operation' => 'Catégorie', 'detail' => 'Alimentation · exemple fictif'],
                ],
            ],
            'stock' => [
                'title' => 'Stocks et achats',
                'icon' => 'package',
                'description' => 'Suivez les entrées, consommations et niveaux de stock de vos produits.',
                'route' => 'stock_matiere_premiere.index',
                'scenario' => 'Comprendre le stock d’aliment',
                'steps' => [
                    'Les achats alimentent le stock à partir des réceptions enregistrées.',
                    'Les sorties et consommations diminuent le stock disponible selon les opérations du module.',
                    'Les chiffres présentés ici sont fictifs : ils ne modifient jamais le stock de votre entreprise.',
                ],
                'sample' => [
                    ['date' => '08/11/2026', 'operation' => 'Stock initial', 'detail' => '600 kg'],
                    ['date' => '10/11/2026', 'operation' => 'Consommation du lot démo A', 'detail' => '− 85 kg'],
                    ['date' => '10/11/2026', 'operation' => 'Stock restant illustratif', 'detail' => '515 kg'],
                ],
            ],
            'ventes' => [
                'title' => 'Ventes et clients',
                'icon' => 'shopping-cart',
                'description' => 'Préparez vos ventes, gérez les clients et retrouvez les factures.',
                'route' => 'vente.index',
                'scenario' => 'Illustrer une vente à un client',
                'steps' => [
                    'Le client est sélectionné ou créé dans le répertoire de l’entreprise.',
                    'Les articles et quantités composent la vente et permettent de produire une facture.',
                    'Une vente réelle peut impacter le stock et les indicateurs financiers selon votre configuration.',
                ],
                'sample' => [
                    ['date' => '14/11/2026', 'operation' => 'Client exemple', 'detail' => 'Boutique du Marché'],
                    ['date' => '14/11/2026', 'operation' => 'Vente illustrative', 'detail' => '20 plateaux · 100 000 FCFA'],
                ],
            ],
            'tresorerie' => [
                'title' => 'Caisse et trésorerie',
                'icon' => 'briefcase',
                'description' => 'Visualisez les comptes et les mouvements de trésorerie de l’entreprise.',
                'route' => 'compte.index',
                'scenario' => 'Lire un mouvement de caisse',
                'steps' => [
                    'Chaque compte représente un moyen ou un emplacement de trésorerie suivi par l’entreprise.',
                    'Les entrées, sorties et transferts doivent rester cohérents avec les opérations enregistrées.',
                    'Le solde d’exemple est calculé uniquement pour cette page de démonstration.',
                ],
                'sample' => [
                    ['date' => '01/11/2026', 'operation' => 'Solde de départ illustratif', 'detail' => '150 000 FCFA'],
                    ['date' => '12/11/2026', 'operation' => 'Sortie fictive', 'detail' => '− 35 000 FCFA'],
                    ['date' => '12/11/2026', 'operation' => 'Solde illustratif', 'detail' => '115 000 FCFA'],
                ],
            ],
        ];
    }
}
