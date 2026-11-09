@if($produit === null)
    <span class="text-center mx-auto col-8 text-warning text-wrap"><strong>Aucun produit de type fiente trouvé</strong></span>
@else
<select  class="select2 form-control" id="id_produit" name="produit" onchange="calcul_prix_unitaire('product','prix_unitaire');" required>
    <option value="{{$produit->id}}" class="product" data-prix="0" selected readonly>
        {{$produit->nom}}
    </option>
</select>
@endif