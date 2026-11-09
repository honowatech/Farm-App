<table class="table">
    <thead>
        <tr class="text-center">
            <th>N°</th>
            <th>Nom</th>
            <th>Date Lancement</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    	@php
        	$count = 20*$bandes->currentPage() -19 ;
            $table_color = [
                'encours' => 'success',
                'terminee' => 'danger',
            ];
    	@endphp
    	@foreach($bandes as $bande)
            <tr class="text-center">
                <td>
                	{{$count}}
                	@php $count++ @endphp
                </td>
                <td>
                    <span class="font-weight-bolder">
                    	{{ucfirst($bande->nom)}}
                    </span>
                </td>
                <td>
                    <span class="badge badge-glow badge-light-dark"> Le
                        @php
                            $date = new DateTime($bande->date_lancement_bande);
                        @endphp
                        {{$date->format('d-M-Y')}}
                    </span>
                </td>
                <td>
                    @if( new DateTime($bande->date_lancement_bande) > new DateTime() )
                        <span class="badge badge-glow badge-dark">
                            {{'En attente'}}
                        </span>
                    @else
                        <span class="badge badge-glow badge-{{$table_color[$bande->statut_bande]}}">
                            {{$bande->statut_bande == 'encours' ? 'En cours' : ucfirst($bande->statut_bande)}}
                        </span>
                    @endif
                </td>
                <td>
                    <div>
                        <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('bande.show',$bande->id)}}" >
                                <i data-feather='eye'></i>
                                <span class="ml-1">Informations</span>
                            </a>
                            <span class="dropdown-item" onclick="actions('form_delete_{{$bande->id}}', 'Supprimer' ,'{{$bande->nom}}')">
                                    <i class="text-danger" data-feather='trash'></i>
                                    <span class="ml-1" type="button">
                                        Supprimer
                                    </span>
                                <form id="form_delete_{{$bande->id}}" action="{{route('bande.destroy',$bande->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </span>
                            @if($bande->statut_bande != 'terminee')
                            <a class="dropdown-item" onclick="response_ajax('info_description1',['{{$bande->id}}'],'{{route('get-form-bande',$bande->id)}}','GET')" data-toggle="modal" data-target="#description1">
                                <i data-feather='minus-circle'></i>
                                <span class="ml-1">Stopper la bande</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>