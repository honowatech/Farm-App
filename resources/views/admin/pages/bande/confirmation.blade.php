<div class="row">
    <div class="col-10 mx-auto mb-3 text-center" >
        Voulez-vous stopper complètement cette bande ?
    </div>
</div>
<div>
    <div class="row text-center mx-auto">
        <button type="button" id="no" class=" col-md-2 col-10 mx-auto btn btn-gradient-danger btn-danger">
            non
        </button>

        <form id="form" action="{{ route('terminer_bande', $bande->id) }}" method="POST" class="col-md-3 col-10 mx-auto">
            @csrf
            @method('PATCH')
            <button type="submit" id="yes" class=" btn btn-gradient-success btn-success">
                oui
            </button>
        </form>
    </div>
</div>