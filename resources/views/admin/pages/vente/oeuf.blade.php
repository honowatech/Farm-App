@if($produits->isEmpty())
    <span class="text-center mx-auto col-8 text-warning text-wrap"><strong>Aucun produit de type oeuf trouvé</strong></span>
@else
<select  class="select2 form-control" id="id_produit" name="produit" onchange="calcul_prix_unitaire('product','prix_unitaire');" required>
    <option value="" class="product" data-prix="0">
        Choisir une provénance
    </option>
    @foreach($produits as $produit)
        @if($produit->type_animal->type_reproduction === "ponte")
            <option class="product" 
                value="{{$produit->id}}" data-prix="{{$produit->prix_unitaire}}" 
                {{$produit->id == old('produit') ? 'selected' : ''}}>
                {{ucfirst($produit->nom)}}
            </option>
        @endif
    @endforeach
</select>
@endif