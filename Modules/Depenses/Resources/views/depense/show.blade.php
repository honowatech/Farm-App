<dl class="row mb-0">
    <dt class="col-sm-4">Motif</dt>
    <dd class="col-sm-8">{{ $depense->motif_depense }}</dd>
    <dt class="col-sm-4">Catégorie</dt>
    <dd class="col-sm-8">{{ $depense->categorie_depense?->nom ?? '—' }}</dd>
    <dt class="col-sm-4">Montant</dt>
    <dd class="col-sm-8">{{ name($depense->montant) }} FCFA</dd>
    <dt class="col-sm-4">Date</dt>
    <dd class="col-sm-8">{{ Ladate($depense->date_depense) }}</dd>
    <dt class="col-sm-4">Fournisseur</dt>
    <dd class="col-sm-8">{{ $depense->fournisseur ?: 'Non renseigné' }}</dd>
    <dt class="col-sm-4">Description</dt>
    <dd class="col-sm-8">{{ $depense->description ?: 'Aucune description' }}</dd>
</dl>
