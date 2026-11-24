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
        @forelse($depenses as $depense)
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
                            <a class="dropdown-item" href="{{ route('depenses.edit', $depense->id) }}">
                                <i data-feather="edit-2"></i>
                                <span class="ml-1">Modifier</span>
                            </a>
                            <a class="dropdown-item" href="#" onclick="response_ajax('info_description', [], '{{ route('depenses.show', $depense->id) }}', 'GET'); return false;" data-toggle="modal" data-target="#description">
                                <i data-feather='eye'></i>
                                <span class="ml-1">Informations</span>
                            </a>

                            <span class="dropdown-item" onclick="actions('form_delete_{{$depense->id}}', 'Supprimer')">
                                    <i class="text-danger" data-feather='trash'></i>
                                    <span class="ml-1" type="button">
                                        Supprimer
                                    </span>
                                <form id="form_delete_{{$depense->id}}" action="{{route('depenses.destroy',$depense->id)}}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="py-2 text-center text-muted">Aucune dépense trouvée.</td></tr>
        @endforelse
    </tbody>
</table>

@if($depenses->hasPages())
    <nav class="d-flex justify-content-center mt-1" aria-label="Pagination des dépenses">
        <ul class="pagination">
            @if($depenses->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Précédent</span></li>
            @else
                <li class="page-item"><a class="page-link" href="#" onclick="mettre({{ $depenses->currentPage() - 1 }}); return false;">Précédent</a></li>
            @endif
            <li class="page-item disabled"><span class="page-link">Page {{ $depenses->currentPage() }} / {{ $depenses->lastPage() }}</span></li>
            @if($depenses->hasMorePages())
                <li class="page-item"><a class="page-link" href="#" onclick="mettre({{ $depenses->currentPage() + 1 }}); return false;">Suivant</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">Suivant</span></li>
            @endif
        </ul>
    </nav>
@endif