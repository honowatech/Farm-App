   <h5>Nouveau logo</h5>
   <form action="{{route('logo.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="row col-12 mx-auto">
            <div class="col-12">
                <div class="form-group">
                    <label for="logo" class="mb-1">Sectionner un logo</label>
                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-0 form-group mx-auto border rounded py-1">
                                <input type="file" name="logo" class="form-control-file col-12 mx-auto" id="logo" required />
                            </div>
                            @if(session()->has('modal') && session()->get('modal') !== 'logo modifier avec succès')
                                <small class="alert alert-danger">{{ session()->get('modal') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row col-12 mx-auto">
            <div class="text-center col-12 mx-auto">
                <button type="submit" id="upload_file" class="btn col-8 mx-auto btn-success mt-1" > 
                    Enregistrer
                </button>
            </div>
            <div class="col-12 text-center">
                <span id="result_save"></span> 
            </div>
        </div>
   </form>
    @php
        session()->forget('modal');
    @endphp


