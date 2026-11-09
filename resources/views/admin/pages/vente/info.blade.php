<div class="row my-5" id="table-without-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Type de mouvement</th>
                            <th>Matiere premiere</th>
                            <th>Ancien Stock</th>
                            <th>Quantite</th>
                            <th>Nouveau Stock</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td class="border">
                                <span class="font-weight-bolder">
                                    {{ucfirst($stock->type_mouvement)}}                   
                                </span>
                            </td>
                            <td class="border">
                                <span class="font-weight-bolder">
                                    {{ucfirst($stock->matiere_premiere->nom_produit)}}                   
                                </span>
                            </td>
                            <td class="border">
                                <span class="font-weight-bolder">
                                    {{$stock->dernier_stock}}                   
                                </span>
                            </td>
                            <td class="border">
                                <span style="font-family: cursive;">
                                    {{$stock->quantite}}                 
                                </span>
                            </td>
                            <td class="border">
                                <span class="font-weight-bolder">
                                    {{$stock->nouveau_stock}}                   
                                </span>
                            </td>
                            <td class="border">
                                <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                                    {{Ladate($stock->date)}}
                                </span>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <center>
        <span>Stock actuel : {{StockMatierepremiere($stock->matiere_premiere)['valeur_en_stock']}} </span>
    </center>
</div>
