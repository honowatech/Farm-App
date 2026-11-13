<div class="row" id="table-without-card">
            <div class="col-12" data-ajax-table>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr class="text-center border">
                            <th>Libellé</th>
                            <th>Abrégé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unites as $unite)
                        <tr class="text-center">
                            <td class="border">
                                <span>
                                    {{ucfirst($unite->libelle)}}                   
                                </span>
                            </td>
                            <td class="border">
                                <span class="font-weight-bolder">
                                    {{ucfirst($unite->abrege)}}               
                                </span>
                            </td>
                            <td class="border">
                                <div>
                                    <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow waves-effect waves-float waves-light" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                    </button>
                                     <div class="dropdown-menu">
                                        <a class="dropdown-item" onclick="response_ajax('info_edition',['admin.pages.setting.unites.edit'],'{{route('unite.edit',$unite->id)}}','GET')" data-toggle="modal" data-target="#edition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            <span class="ml-1">Editer</span>
                                        </a>
                                        <span class="text-danger dropdown-item" data-toggle="modal" onclick="remplir('{{route('unite.destroy',$unite->id)}}','{{$unite->libelle}}','Supprimer','button_footer')" data-target="#danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            <span class="ml-1 text-danger" type="button">
                                                Supprimer
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-1">
            {{ $unites->links() }}
        </div>
    </div>
</div>
<div class="modal fade" id="edition" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="border modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Boite d'édition </h5>
                <button type="button" class="close m-0 bg-danger" onclick="close_modale('edition')">
                    <span class="text-white" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12 mx-auto" id="info_edition">
                    Editer une unité!
                </div>
            </div>
        </div>
    </div>
</div>
