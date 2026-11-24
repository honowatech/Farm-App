<?php

namespace App\Support;

/**
 * Résout le tour interactif (spotlight) d'une route donnée.
 *
 * Priorité :
 * 1. la surcharge explicite `config('onboarding.tours.{route}')` ;
 * 2. la génération générique à partir de `config('onboarding.entities')`
 *    pour les routes au format `{entite}.{index|create|edit}`.
 */
class FeatureTour
{
    /**
     * @return array{title: string, key: string, steps: array<int, array{target: string, title: string, text: string}>}|null
     */
    public static function forRoute(?string $route): ?array
    {
        if (! is_string($route) || $route === '') {
            return null;
        }

        $tours = config('onboarding.tours', []);

        if (array_key_exists($route, $tours) && is_array($tours[$route])) {
            return $tours[$route] + ['key' => $route];
        }

        return self::generated($route);
    }

    private static function generated(string $route): ?array
    {
        if (! preg_match('/^([a-zA-Z0-9_]+)\.(index|create|edit)$/', $route, $matches)) {
            return null;
        }

        [, $entity, $action] = $matches;

        $info = config('onboarding.entities.'.$entity);

        if (! is_array($info)) {
            return null;
        }

        $title = $info['title'] ?? ucfirst(str_replace('_', ' ', $entity));
        $noun = $info['noun'] ?? 'un enregistrement';
        $createLabel = array_key_exists('create', $info) ? $info['create'] : 'Créer';

        $steps = match ($action) {
            'index' => self::indexSteps($title, $noun, $createLabel),
            'create', 'edit' => self::formSteps($noun, $action),
            default => [],
        };

        return [
            'title' => $title,
            'key' => $route,
            'steps' => $steps,
        ];
    }

    /**
     * @return array<int, array{target: string, title: string, text: string}>
     */
    private static function indexSteps(string $title, string $noun, ?string $createLabel): array
    {
        $steps = [];

        if (is_string($createLabel) && $createLabel !== '') {
            $steps[] = [
                'target' => '[data-tour-step="create"]',
                'title' => $createLabel,
                'text' => "Ajoutez $noun.",
            ];
        }

        $steps[] = [
            'target' => '[data-tour-step="search"]',
            'title' => 'Rechercher',
            'text' => 'Recherchez ou filtrez la liste.',
        ];

        $steps[] = [
            'target' => '[data-tour-step="table"]',
            'title' => 'Liste',
            'text' => "Consultez et gérez les $title.",
        ];

        return $steps;
    }

    /**
     * @return array<int, array{target: string, title: string, text: string}>
     */
    private static function formSteps(string $noun, string $action): array
    {
        $verb = $action === 'edit' ? 'Modifiez' : 'Renseignez';

        return [
            [
                'target' => '[data-tour-step="fields"]',
                'title' => 'Renseignez le formulaire',
                'text' => "$verb les informations de $noun.",
            ],
            [
                'target' => '[data-tour-step="actions"]',
                'title' => 'Enregistrer',
                'text' => 'Validez pour sauvegarder les modifications.',
            ],
        ];
    }
}
