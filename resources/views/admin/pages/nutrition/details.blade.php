<table class="table">
    <thead>
        <tr class="text-center">
            <th>N°</th>
            <th>Bande</th>
            <th>aliment</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	@php
        	$count = 20*$nutritions->currentPage() -19 ;
    	@endphp
    	@foreach($nutritions as $nutrition)
            <tr class="text-center">
                <td>
                	{{$count}}
                	@php $count++ @endphp
                </td>
                <td>
                    <span class="font-weight-bolder">
                        {{ucfirst($nutrition->bande_name->nom)}}                   
                    </span>
                </td>
                <td>
                    <span class="font-weight-bolder text-truncate">
                        {{ucfirst($nutrition->aliment_name->nom_produit)}}                
                    </span>
                </td>
                <td>
                    <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                        {{Ladate($nutrition->date)}}
                    </span>
                </td>
                <td>
                    <div>
                        <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" onclick="response_ajax('info_description',['{{$nutrition->id}}'],'{{route('nutrition.show',$nutrition->id)}}','GET')" data-toggle="modal" data-target="#description">
                                <i data-feather='eye'></i>
                                <span class="ml-1">Informations</span>
                            </a>
                            <a class="dropdown-item" href="{{route('nutrition.edit',$nutrition->id)}}">
                                <i data-feather='edit'></i>
                                <span class="ml-1">Editer</span>
                            </a>
                            <span class="dropdown-item" onclick="actions('form_delete_{{$nutrition->id}}', 'Supprimer le planning d\'alimentation des' ,'{{$nutrition->nom}}')">
                                    <i class="text-danger" data-feather='trash'></i>
                                    <span class="ml-1" type="button">
                                        Supprimer
                                    </span>
                                <form id="form_delete_{{$nutrition->id}}" action="{{route('nutrition.destroy',$nutrition->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>