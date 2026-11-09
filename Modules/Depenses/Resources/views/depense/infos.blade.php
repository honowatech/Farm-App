<table class="table">
    <thead>
        <tr class="text-center">
            <th>N°</th>
            <th>Motif de la dépense</th>
            <th>Montant</th>
            <th>Fournisseur</th>
            <th>Date </th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    	@php
        	$count = 20*$depenses->currentPage() -19 ;
    	@endphp
    	@foreach($depenses as $depense)
            <tr class="text-center">
                <td>
                	{{$count}}
                	@php $count++ @endphp
                </td>
                <td>
                    <span class="font-weight-bolder">
                         {{ucfirst($depense->motif_depense)}}                  
                    </span>
                </td>
                <td>
                    <span class="text-center text-dark font-weight-bolder">
                        {{name($depense->montant)}} FCFA
                    </span>
                </td>
                <td>
                    <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                       {{ucfirst($depense->fournisseur)}}
                    </span>
                </td>
                <td>
                    <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                       {{name($depense->date_depense)}}
                    </span>
                </td>
                <td>
                    <div>
                        <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item"  data-toggle="modal" data-target="#description">
                                <i data-feather='eye'></i>
                                <span class="ml-1">Informations</span>
                            </a>

                            <span class="dropdown-item" onclick="actions('form_delete_{{$depense->id}}', 'Supprimer')">
                                    <i class="text-danger" data-feather='trash'></i>
                                    <span class="ml-1" type="button">
                                        Supprimer
                                    </span>
                                <form id="form_delete_{{$depense->id}}" action="{{route('depenses.destroy',$depense->id)}}" method="POST">
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

<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto" id="info_description">
                        Infos de chaque Batîment ici !
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>