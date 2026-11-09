    <h5>Editer l'unité {{$unite->libelle}}</h5>
    <div class="row col-12 mx-auto">
        <div class="col-12">
            <div class="form-group">
                <label for="libelle">Libelle</label>
                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                <div class="form-group">
                    <input type="text" id="libelle" class="form-control" name="libelle" placeholder="Libellé" value="{{$unite->libelle}}" / required>
                </div>
                <small class="alert alert-danger"></small>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="abrege">Abrege</label>
                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                <div class="form-group">
                    <input type="text" id="abrege" class="form-control " name="abrege" placeholder="Abrégé" value="{{$unite->abrege}}" / required>
                </div>
                <small class="alert alert-danger"></small>
            </div>
        </div>
    </div>
    <div class="row col-12 mx-auto">
        <div class="text-center col-12 mx-auto">
            <button type="submit" id="save_unite" class="btn col-8 mx-auto btn-success mt-1" onclick="savegarder('save_unite','PATCH','result_save',['libelle','abrege'],'{{route('unite.update',$unite->id)}}',['info_gestion','','{{route('unite.index')}}','GET'],'edition')"> 
                Enregistrer
            </button>
        </div>
        <div class="col-12 text-center">
            <span id="result_save"></span> 
        </div>
    </div>
