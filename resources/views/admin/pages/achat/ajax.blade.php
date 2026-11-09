@if($details->count() == 0)
    <option  value="">
        Aucun fournisseur trouvé
    </option>
@else
    <option value="">
        Choisir Le fournisseur
    </option>
    @foreach($details as $detail)
        @if($detail->fournisseur->statut == 1)
            <option class="product" value="{{$detail->id_fournisseur}}" {{$detail->id_fournisseur == session()->get('fournisseur') ? 'selected' : ''}}>
                {{ucfirst($detail->fournisseur->nom)}}
            </option>
        @endif
    @endforeach
@endif