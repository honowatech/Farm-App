<!-- Table without card start -->
<div>
	<h3 class="text-center m-1">
		{{$title}}
	</h3>
</div>
<div class="row m-1 border" id="table-without-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr class="text-center">
                    <th class="border">N°</th>
                    <th class="border">Nom</th>
                    <th class="border">Prix</th>
                    <th class="border">Actions</th>
                    </tr>
                </thead>
            	<tbody>
                    @php
                        $count = 1 ;
                    @endphp
                    @foreach($matiere_premieres as $matiere_premiere)
	                <tr class="text-center">
	                    <td class="border">
	                        {{$count}}
	                        @php $count++ @endphp
	                    </td>
	                    <td class="border">
	                        <span class="font-weight-bolder">
	                   			{{ucfirst($matiere_premiere->nom_produit)}}
	                        </span>
	                    </td>
	                    <td class="border">
	                   		{{$matiere_premiere->prix_unitaire}} FCFA
	                    </td>
	                    <td class="border">
	                        <div>
	                            <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                            	<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical">
	                            		<circle cx="12" cy="12" r="1"></circle>
	                            		<circle cx="12" cy="5" r="1"></circle>
	                            		<circle cx="12" cy="19" r="1"></circle>
	                            	</svg>
	                            </button>
	                            <div class="dropdown-menu">
	                                <a class="dropdown-item" onclick="response_ajax('info_description',['{{$matiere_premiere->id}}'],'{{route('matiere_premiere.show',$matiere_premiere->id)}}','GET')" data-toggle="modal" data-target="#description">
	                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
	                                    <span class="ml-1">Détails</span>
	                                </a>
                                    <a class="dropdown-item" href="{{route('matiere_premiere.edit',$matiere_premiere->id)}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span class="ml-1">Modifier</span>
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