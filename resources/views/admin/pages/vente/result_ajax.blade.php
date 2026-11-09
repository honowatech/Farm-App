<table class="table">
    <thead>
        <tr class="text-center">
            <th>N°</th>
            <th>Nom client</th>
            <th>Nom Fournisseur</th>
            <th>Date </th>
            <th>Total </th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	@php
        	$count = 20*$ventes->currentPage() -19 ;
            $img = [
                'mobilemoney' => 'app-assets/images/logo/mtn.svg',
                'orangemoney' => 'app-assets/images/logo/orange.png',
                'espece' => 'app-assets/images/logo/espece.webp',
                'bank' => 'app-assets/images/logo/bank.png',
            ];
            $title = [
                'mobilemoney' => 'Mtn MOMO',
                'orangemoney' => 'Orange Money',
                'espece' => 'Espèce',
                'bank' => 'Paiement Bancaire'
            ];
    	@endphp
    	@foreach($ventes as $vente)
            <tr class="text-center">
                <td>
                	{{$count}}
                	@php $count++ @endphp
                </td>
                <td>
                    <span class="font-weight-bolder">
                        {{ucfirst($vente->facture->client_facture->nom)}}                   
                    </span>
                </td>
                <td>
                    <span class="text-center text-dark font-weight-bolder">
                        {{name($vente->saver->name)}}
                    </span>
                </td>
                <td>
                    <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                        {{Ladate($vente->date_vente)}}
                    </span>
                </td>
                <td>
                    <span class="font-weight-bolder text-truncate">
                        {{ucfirst($vente->facture->total_ttc)}} FCFA                 
                    </span>
                </td>
                <td>
                    <div>
                        <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{route('facturation',$vente->id)}}">
                                <i data-feather='archive'></i>
                                <span class="ml-1">Detail transaction</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>