<table  id="myTable" class="table table-striped table-borderless">
    <thead class="thead-light">
        <tr class="text-center">
            <th>N°</th>
            <th>{{__('salarie::translate.Type')}}</th>
            <th>{{__('salarie::translate.Montant')}}</th>
            <th>{{__('salarie::translate.Echéance')}}</th>
            <th>{{__('salarie::translate.date de paiement')}}</th>
            <th>{{__('salarie::translate.Action')}}</th>
        </tr>
    </thead>
    <tbody>
            @php
                $row = 10*$paiements->currentPage() -9 ;
            @endphp
            @foreach($paiements as $paiement)
                <tr class="text-center">
                    <td>
                        {{$row}}
                    </td>
                    <td>
                            @if($paiement->type == 'paiement_complet')
                        <div class="badge badge-glow badge-success">
                            @else
                        <div class="badge badge-glow badge-warning" style="background: #ff0000 !important">
                            @endif
                            {{__('salarie::translate.'.$types_Paiement[$paiement->type])}}
                        </div>
                    </td>
                    <td class="text-truncate ">
                        <b>{{$paiement->montant}} FCFA</b>
                    </td>
                    <td>
                        <div class="badge badge-glow badge-dark">
                            Le {{Ladate($paiement->date_fin)}} à {{Heure($paiement->date_fin)}}
                        </div>
                    </td>
                    <td>
                        <div class="badge badge-glow badge-dark">
                            Le {{Ladate($paiement->date_paiement)}} à {{Heure($paiement->date_paiement)}}
                        </div>
                    </td>
                    <td>
                        <div>
                            <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="response_ajax('info_description',['{{$paiement->id}}'],'{{route('paiement_salaire.show',$paiement->id)}}','GET')" data-toggle="modal" data-target="#description">
                                    <i data-feather='eye'></i>
                                    <span class="ml-1">Informations</span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>

            @php
                $row++
            @endphp
            @endforeach
    </tbody>
</table>