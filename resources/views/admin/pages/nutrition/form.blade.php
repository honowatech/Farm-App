{{-- /resources/views/admin/pages/nutrition/form.blade.php --}}
{{--
    Cette vue est chargée via AJAX par la méthode `take_form` du `NutritionController`.
    Variables disponibles : $bande, $age, $nbre_sujet, $animal_name, $nutritions_prevues, $aliments_concernes
--}}

@if($nutritions_prevues->isEmpty())
    <div class="p-1 text-center rounded bg-light-warning col-12">
        <span class="font-weight-bolder text-warning">
            Aucun planning alimentaire trouvé pour les animaux de type "{{$animal_name}}" âgés de {{$age}} jour(s) !
        </span>
        <br>
        <a href="{{ route('foods-setting.index') }}">Cliquez ici pour configurer un planning.</a>
    </div>
@else
    {{-- On utilise un ID unique pour le formulaire partiel pour éviter les conflits --}}
    <div id="dynamic-nutrition-form-content" class="row col-12">
        <!-- Choix de l'aliment -->
        <div class="py-1 col-12 col-md-6">
            <div class="form-group">
                <label for="aliment-select">Aliment à donner<span class="text-danger">*</span></label>
                <select id="aliment-select" class="form-control @error('id_aliment') is-invalid @enderror" name="id_aliment" required>
                    <option value="" disabled selected>Choisissez un aliment</option>
                    @foreach($aliments_concernes as $item)
                        @php
                            $planning = $nutritions_prevues->firstWhere('aliment', $item['aliment']->id);
                            if (!$planning) continue; // Sécurité si un aliment n'a pas de planning
                            $passagesFaits = $item['passages'];
                            $passagesAttendus = $planning->frequency;
                            $estComplet = $passagesFaits >= $passagesAttendus;
                        @endphp
                        <option value="{{ $planning->aliment }}"
                                data-quantite-attendue="{{ $nbre_sujet * $planning->quantite_aliment }}"
                                data-unite-id="{{ $planning->id_unite }}"
                                data-unite-nom="{{ $planning->unite->abrege ?? 'N/A' }}"
                                {{ $estComplet ? 'disabled' : '' }}>
                            {{ $item['aliment']->nom_produit }} ({{ $passagesFaits }}/{{ $passagesAttendus }}) {{ $estComplet ? '✅' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_aliment')
                    <small class="mt-1 text-danger d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <!-- Date de la nutrition -->
        <div class="py-1 col-md-6 col-12">
            <div class="form-group">
                <label for="date">Date de nutrition<span class="text-danger">*</span></label>
                <input type="text" id="date" class="form-control flatpickr-date-time @error('date') is-invalid @enderror"
                       name="date" placeholder="YYYY-MM-DD HH:MM"
                       value="{{ old('date', now()->format('Y-m-d H:i')) }}" required>
                @error('date')
                    <small class="mt-1 text-danger d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <!-- Quantité d'aliment donnée -->
        <div class="py-1 col-md-6 col-12">
            <div class="form-group">
                <label for="qte">Quantité donnée<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control @error('qte') is-invalid @enderror"
                           id="qte" name="qte" value="{{ old('qte') }}" placeholder="Ex: 50.5" min="0.01" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="unite-label">Unité</span>
                    </div>
                </div>
                <small id="quantite-attendue-helper" class="form-text text-muted" style="display: none;">
                    Quantité attendue : <strong id="valeur-attendue"></strong>
                </small>
                @error('qte')
                    <small class="mt-1 text-danger d-block">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <input type="hidden" name="id_unite" id="id_unite_hidden" value="{{ old('id_unite') }}">
        <input type="hidden" name="unite_string" id="unite_string_hidden" value="{{ old('unite_string') }}">

        <!-- Description (Optionnel) -->
        <div class="py-1 col-md-6 col-12">
            <div class="form-group">
                <label for="description">Description (Optionnel)</label>
                <textarea id="description" class="form-control" name="description" rows="1">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <script>
        // Ce script est encapsulé dans une fonction auto-exécutante pour éviter les conflits
        // et s'assure qu'il est ré-exécuté à chaque fois que ce bloc HTML est chargé par AJAX.
        (function() {
            // --- Initialisation des Plugins ---

            // Initialise le sélecteur de date et d'heure (Flatpickr)
            if (typeof flatpickr !== 'undefined') {
                flatpickr('.flatpickr-date-time', {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    defaultDate: 'today' // Pré-remplit avec la date et l'heure actuelles
                });
            }

            // --- Récupération des Éléments du DOM ---

            const alimentSelect = document.getElementById('aliment-select');
            // Si le sélecteur d'aliment n'existe pas, on arrête le script pour cette vue.
            if (!alimentSelect) return;

            const quantiteInput = document.getElementById('qte');
            const uniteLabel = document.getElementById('unite-label');
            const uniteIdHidden = document.getElementById('id_unite_hidden');
            const uniteStringHidden = document.getElementById('unite_string_hidden'); // Le nouveau champ caché
            const helperText = document.getElementById('quantite-attendue-helper');
            const helperTextValue = document.getElementById('valeur-attendue');

            // --- Logique Événementielle ---

            // Attache un écouteur d'événement sur le changement de l'aliment sélectionné
            alimentSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                // Si l'utilisateur sélectionne l'option par défaut ("Choisissez un aliment")
                if (!selectedOption || !selectedOption.value) {
                    // On réinitialise tous les champs liés
                    quantiteInput.value = '';
                    uniteLabel.textContent = 'Unité';
                    uniteIdHidden.value = '';
                    uniteStringHidden.value = ''; // Réinitialiser aussi ce champ
                    helperText.style.display = 'none';
                    return; // On arrête l'exécution de la fonction
                }

                // Récupérer les données stockées dans les attributs `data-*` de l'option sélectionnée
                const quantiteAttendue = selectedOption.dataset.quantiteAttendue;
                const uniteNom = selectedOption.dataset.uniteNom;
                const uniteId = selectedOption.dataset.uniteId;

                // Mettre à jour les champs du formulaire avec les données de l'aliment
                // 1. Pré-remplir la quantité avec la valeur attendue, formatée à 2 décimales
                quantiteInput.value = parseFloat(quantiteAttendue).toFixed(2);

                // 2. Mettre à jour le label visible de l'unité (ex: 'Kg')
                uniteLabel.textContent = uniteNom;

                // 3. Mettre à jour le champ caché pour l'ID de l'unité (ex: '1')
                uniteIdHidden.value = uniteId;

                // 4. Mettre à jour le champ caché pour le nom de l'unité en minuscules (ex: 'kg')
                uniteStringHidden.value = uniteNom.toLowerCase();

                // 5. Mettre à jour et afficher le message d'aide sous le champ quantité
                helperTextValue.textContent = `${parseFloat(quantiteAttendue).toFixed(2)} ${uniteNom}`;
                helperText.style.display = 'block';
            });

        })(); // Fin de la fonction auto-exécutante
    </script>
@endif
