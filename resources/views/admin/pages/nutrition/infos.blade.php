

<div class="text-center">Nutrition concernant les annimaux de la bande dont le nom est <span class="badge badge-glow badge-secondary">{{$nutrition->bande_name->nom}}</span></div>
<hr>
<div class="pb-1">
     <b>Date de nutrition: </b><span class="badge badge-glow badge-secondary">{{$nutrition->date}}</span><br>
     <b>Nom de l'aliment: </b><span class="badge badge-glow badge-secondary">{{$nutrition->aliment_name->nom_produit}}</span><br>
     <b>Quantité d'aliment fournis : </b><span class="badge badge-glow badge-secondary">{{$nutrition->qte}} {{ $nutrition->unite ? $nutrition->unite->libelle : 'aucune unité' }} </span>
</div>
