{{-- Confirmation de suppression partagée par les listes. --}}
<div id="button_footer" hidden>
    <button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
    <form method="POST" class="p-0 m-0 mx-auto destroy_form col-md-5 col-10">
        @csrf
        @method('DELETE')
        <button type="submit" class="col-12 btn btn-gradient-success btn-success">Confirmer</button>
    </form>
</div>
