{{-- On utilise isNotEmpty() qui est souvent plus lisible que !isEmpty() --}}
@if($nutritions->isNotEmpty())

    @php
        // Étape 1: Obtenir le nom de l'animal une seule fois.
        // Puisque tous les enregistrements concernent le même animal, on prend le premier.
        $animalName = $nutritions->first()->planning->nom;

        // Étape 2: Grouper tous les enregistrements par le nom de l'aliment.
        // Cela crée une structure parfaite pour l'affichage :
        // 'Aliment A' => [détails pour 0-10j, détails pour 11-20j],
        // 'Aliment B' => [détails pour 0-5j, ...]
        $groupedNutritions = $nutritions->groupBy('alimentName.nom_produit');
    @endphp

    <!-- Titre principal du Planning -->
    <div class="mb-2 text-center">
        <h4>Planning Alimentaire pour les <span class="badge badge-success">{{ $animalName }}</span></h4>
        <hr>
    </div>

    <!-- Boucle sur chaque groupe d'aliment -->
    @foreach($groupedNutritions as $alimentName => $nutritionDetails)
        <div class="mb-2 card">
            <div class="card-header bg-light">
                <h5 class="mb-0 card-title">Aliment : <span class="font-weight-bold text-primary">{{ $alimentName }}</span></h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Tranche d'âge (jours)</th>
                            <th>Quantité par animal</th>
                            <th>Fréquence</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Boucle sur les détails de nutrition pour cet aliment, triés par âge min --}}
                        @foreach($nutritionDetails->sortBy('age_min') as $nutrition)
                            <tr>
                                <td class="text-center">
                                    De <strong>{{ $nutrition->age_min }}</strong> à <strong>{{ $nutrition->age_max }}</strong>
                                </td>
                                <td>
                                    {{-- Utilise l'abréviation de l'unité si elle existe (ex: Kg), sinon le libellé complet --}}
                                    <strong>{{ $nutrition->quantite_aliment }}</strong> {{ $nutrition->unite->abrege ?? $nutrition->unite->libelle }}
                                </td>
                                <td>
                                    {{-- Logique corrigée pour la période (jour, semaine, mois) --}}
                                    @php
                                        $periode_texte = '';
                                        switch ($nutrition->period) {
                                            case 'day':
                                                $periode_texte = 'jour';
                                                break;
                                            case 'week':
                                                $periode_texte = 'semaine';
                                                break;
                                            case 'month':
                                                $periode_texte = 'mois';
                                                break;
                                            default:
                                                $periode_texte = $nutrition->period; // Fallback
                                        }
                                    @endphp
                                    <strong>{{ $nutrition->frequency }}</strong> fois par {{ $periode_texte }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

@else
    {{-- Affichage pour le cas où il n'y a aucun planning --}}
    <div class="p-2 text-center rounded border bg-light">
        <h5 class="text-danger">Aucun planning d'alimentation défini</h5>
        <p class="mb-0 text-muted">Aucun détail n'est disponible pour ce type d'animal.</p>
    </div>
@endif