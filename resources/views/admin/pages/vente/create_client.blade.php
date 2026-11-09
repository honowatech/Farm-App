<form class="form" id="register" method="POST">
	@csrf
    <div class="row col-lg-10 mx-auto">
        <div class=" col-12 mx-auto">
            <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                <div class="form-group">
                    <div class="input-group input-group-merge @error('nom') is-invalid @enderror">
                        <div class="input-group-prepend ">
                            <span class="input-group-text"><i data-feather="at-sign"></i></span>
                        </div>
                        <input type="text" id="nom" class="form-control @error('nom') is-invalid @enderror" name="nom" placeholder="Nom" value="{{ old('nom') }}" / required autofocus>
                    </div>
                    @error('nom')
                    <small class="alert alert-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class=" col-12 mx-auto">
            <div class="form-group">
                    <label for="email-icon">Email</label>
                    <span class="text-info cursor-pointer" title="Optionnel">Optionnel</span>
                <div class="form-group">
                    <div class="input-group input-group-merge  @error('email') is-invalid @enderror">
                        <div class="input-group-prepend ">
                            <span class="input-group-text"><i data-feather="mail"></i></span>
                        </div>
                        <input type="email" autocomplete="" id="email-icon" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}"/>
                    </div>
                    @error('email')
                    <small class="alert alert-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class=" col-12 mx-auto">
            <div class="form-group">
                <label for="password">Mot de Passe</label>
                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                <div class="form-group">
                    <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
                    	<div class="input-group-prepend">
                    		<span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>
                        <input type="password" autocomplete="" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Mot de Passe" value="" name="password"aria-describedby="basic-default-password1"  required/>
                    </div>
                    @error('password') 
                    	<small class="alert alert-danger"> {{$message}} </small>
                    @enderror
                </div>
            </div>
        </div>
        <div class=" col-12 mx-auto">
            <div class="form-group">
                    <label for="telephone">Numéro de téléphone</label>
                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                <div class="form-group">
                    <div class="input-group input-group-merge  @error('telephone') is-invalid @enderror">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="mr-1 flag-icon flag-icon-cm"></i>
                                +237
                            </span>
                        </div>
                        <input type="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" placeholder="Numéro de téléphone" value="{{ old('telephone') }}" onkeyup="phone('telephone');" required/>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i data-feather="phone"></i>
                            </span>
                        </div>
                    </div>
                    <input type="text" hidden id="telephone2" name="telephone"/>
                    @error('telephone')
                            <small class="alert alert-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class=" col-12 mx-auto">
            <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <span class="text-info cursor-pointer" title="Optionnel">Optionnel</span>
                <div class="form-group">
                    <div class="input-group input-group-merge @error('adresse') is-invalid @enderror">
                        <div class="input-group-prepend ">
                            <span class="input-group-text"><i data-feather="map-pin"></i></span>
                        </div>
                        <input type="text" id="adresse" class="form-control @error('adresse') is-invalid @enderror" name="adresse" placeholder="Adresse" value="{{ old('adresse') }}" />
                    </div>
                    @error('adresse')
                    <small class="alert alert-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="row col-12">
        <div class="d-none d-md-block text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
             <button type="reset" class="btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-danger mt-1 mt-md-2 mr-md-4">
                <i class=" fa fa-close" data-feather='x'></i> 
           </button>
            <button type="submit" onclick="STForm('cabrel','{{ route('saving')}}','POST','register','client_creer')"  class="soumission btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-1 mt-md-2">
                <i class=" fa fa-download" data-feather='download'></i> 
            </button>
        </div>
        <div class="d-block d-md-none text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
            <button type="submit"  onclick="STForm('cabrel','{{ route('saving')}}','POST','register','client_creer', )" class="soumission btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-success mt-1 mt-md-2 mr-md-2">
                <i class=" fa fa-close" data-feather='download'></i> 
            </button>
            <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-1 mt-md-2">
                <i class=" fa fa-download" data-feather='x'></i>
            </button>
        </div>
    </div>
</form>