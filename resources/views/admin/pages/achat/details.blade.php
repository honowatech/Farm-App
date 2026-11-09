
<div class="row" id="table-without-card">
        <div class="table-responsive">
            <table class="table" id="search">
                <thead>
                    <tr class="text-center">
                        <th>N°</th>
                        <th>Aliment</th>
                        <th>Fournisseur</th>
                        <th>Prix unitaire </th>
                        <th>Qte</th>
                        <th>Date</th>
                        <th>Mode de paiement</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	@php
                    	$count = 20*$achats->currentPage() -19 ;
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
                	@foreach($achats as $achat)
                        <tr class="text-center">
                            <td>
                            	{{$count}}
                            	@php $count++ @endphp
                            </td>
                            <td>
                                <span class="font-weight-bolder">
                                    {{ucfirst($achat->matiere_premiere->nom_produit)}}                   
                                </span>
                            </td>
                            <td>
                                <span class="text-center text-dark font-weight-bolder">
                                @if($achat->fournisseur == null)
                                        <div class="spinner-grow spinner-grow-sm" role="status">
                                        </div>
                                @else 
                                    {{name($achat->fournisseur->nom)}}
                                @endif
                                </span>
                            </td>
                            <td>
                                <span class="font-weight-bolder text-truncate">
                                    {{ucfirst($achat->matiere_premiere->prix_unitaire)}} FCFA                 
                                </span>
                            </td>
                            <td>
                                <span style="font-family: cursive;">
                                    {{$achat->qte}}                 
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le 
                                    {{Ladate($achat->date)}}
                                </span>
                            </td>
                            <td>
                                <div class="avatar" title="{{$title[$achat->mode_de_paiement]}}">
                                    <img src="{{asset($img[$achat->mode_de_paiement])}}" alt="avatar" style="height: 40px; width: 50px;">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                        <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" onclick="response_ajax('info_description',['{{$achat->id}}'],'{{route('achat.show',$achat->id)}}','GET')" data-toggle="modal" data-target="#description">
                                            <i data-feather='archive'></i>
                                            <span class="ml-1">Ligne de stock</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if ($achats->hasPages())
        <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
            <div class="row mx-auto justify-content-center flex-fill d-md-none">
                <div class="mr-1 d-md-none">
                    <p class="small text-muted">
                        {!! __('De') !!}
                        <span class="fw-semibold">{{ $achats->firstItem() }}</span>
                        {!! __('à') !!}
                        <span class="fw-semibold">{{ $achats->lastItem() }}</span>
                        {!! __('sur') !!}
                        <span class="fw-semibold">{{ $achats->total() }}</span>
                        {!! __('resultats') !!}
                    </p>
                </div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($achats->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">Précédent</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" onclick="mettre({{ $achats->currentPage()-1 }})" rel="prev">Suivant</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($achats->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" onclick="mettre({{ $achats->currentPage()+1 }})" rel="next">@lang('pagination.next')</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">@lang('pagination.next')</span>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="d-none flex-md-fill d-md-flex align-items-md-center justify-content-md-center">
                <div class="mr-1">
                    <p class="small text-muted">
                        {!! __('vu de ') !!}
                        <span class="fw-semibold">{{ $achats->firstItem() }}</span>
                        {!! __('à') !!}
                        <span class="fw-semibold">{{ $achats->lastItem() }}</span>
                        {!! __('sur') !!}
                        <span class="fw-semibold">{{ $achats->total() }}</span>
                        {!! __('resultats') !!}
                    </p>
                </div>

                <div>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($achats->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" onclick="mettre({{ $achats->currentPage()-1 }})" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($achats->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $achats->currentPage())
                                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" onclick="mettre({{ $page }})">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($achats->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" onclick="mettre({{ $achats->currentPage()-1 }})" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                <span class="page-link" aria-hidden="true">&rsaquo;</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    @endif
</div>