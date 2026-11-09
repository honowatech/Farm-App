
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Bande</th>
                                        <th>Produit</th>
                                        <th>Type d'animal</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	@php
                                    	$count = 20*$ramassages->currentPage() -19 ;
                                	@endphp
                                	@foreach($ramassages as $ramassage)
	                                    <tr class="text-center">
	                                        <td>
	                                        	{{$count}}
	                                        	@php $count++ @endphp
	                                        </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->bande->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->produit->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    {{ucfirst($ramassage->produit->type_animal->nom)}}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-glow badge-light-dark"> 
                                                    @php
                                                        $date = new DateTime($ramassage->to_do_date);
                                                    @endphp
                                                    {{$date->format('d-M-Y')}}
                                                </span> à
                                                <span class="badge badge-glow badge-light-dark">
                                                    {{$date->format('H')}}h:{{$date->format('i')}}
                                                </span> 
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-glow @if($ramassage->statut == 0) badge-danger @else badge-success @endif">
                                                    @if($ramassage->statut == 0)
                                                        Supprimé
                                                    @else 
                                                        Effectué
                                                    @endif
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#infos{{$ramassage->id}}">
                                                            <i data-feather='shopping-bag'></i>
                                                            <span class="ml-1">Détails</span>
                                                        </a>
                                                        {{-- <a class="dropdown-item" href="{{route('ramassage.edit',$ramassage->id)}}">
                                                            <i data-feather='edit-2'></i>
                                                            <span class="ml-1">Modifier</span>
                                                        </a> --}}
                                                        @if($ramassage->statut == 1)
                                                            <span class="dropdown-item" onclick="actions('form_delete_{{$ramassage->id}}', 'Supprimer' )">
                                                                    <i class="text-danger" data-feather='trash'></i>
                                                                    <span class="ml-1" type="button">
                                                                        Supprimer
                                                                    </span>
                                                                <form id="form_delete_{{$ramassage->id}}" action="{{route('ramassage.destroy',$ramassage->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </span>
                                                        @endif
                                                    </div>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                @endforeach
                                </tbody>
                            </table>