@if($produits->isEmpty())
    <span class="text-center mx-auto col-8 text-warning text-wrap"><strong>aucun produit de type animal trouvé</strong></span>
@else
<select  class="select2 form-control" id="id_produit" name="produit" onchange="calcul_prix_unitaire('product','prix_unitaire');" required>
    <option value="" class="product" data-prix="0">
        Choisir un animal
    </option>
    @foreach($produits as $produit)
        @if($produit->statut != 0)
            <option class="product" 
                value="{{$produit->id}}" data-prix="{{$produit->prix_unitaire}}" 
                {{$produit->id == old('produit') ? 'selected' : ''}}>
                {{ucfirst($produit->nom)}}
            </option>
        @endif   
    @endforeach
</select>
@endif