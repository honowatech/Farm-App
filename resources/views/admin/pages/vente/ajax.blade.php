

    <div class="form-group">
        <label for="id_produit">Nom client</label>
        <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
        <div class="form-group">
            <select class="select2 form-control" id="id_client" name="client" required>
                <option value="" class="" data-prix="0">
                    Choisir un client
                </option>
                @foreach($clients as $client)
                    <option class="product" 
                        value="{{$client->id}}" 
                        {{$client->id == old('client') ? 'selected' : ''}}>
                        {{ucfirst($client->nom)}}
                    </option>
                @endforeach
            </select>
            <div id="error_client" class="d-none">
                <small class="alert alert-danger">veuillez remplir ce champ</small>
            </div>
        </div>
    </div>
