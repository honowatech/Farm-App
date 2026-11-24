<div data-tour-step="table" class="row" id="table-without-card">
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
                        @forelse($unites as $unite)
                        <tr class="text-center">
                            <td class="border">
                                {{ ucfirst($unite->libelle) }}
                            </td>
                            <td class="border">
                                <span class="font-weight-bolder">{{ ucfirst($unite->abrege) }}</span>
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
                                        <form method="POST" action="{{ route('unite.destroy', $unite->id) }}" onsubmit="return confirm('Supprimer cette unité ?')" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i data-feather="trash"></i>
                                                <span class="ml-1">Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-2 text-center text-muted">Aucune unité enregistrée pour cette entreprise.</td>
                        </tr>
                        @endforelse
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
