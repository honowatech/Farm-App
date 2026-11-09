
@if($produits->count() == 0)
    <option value="">
        Aucun Produit pour la bande
    </option>
@else
    <option value="">
        Choisir Le produit
    </option>
    @foreach($produits as $produit)
        @if($produit->derives->count() > 0)
            <option class="product" 
                value="{{$produit->id}}"
                {{$produit->id == old('id_produit') ? 'selected' : ''}}>
                {{ucfirst($produit->nom)}}
            </option>
        @endif
    @endforeach
@endif