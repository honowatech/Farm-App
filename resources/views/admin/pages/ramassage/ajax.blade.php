@php
    $row = 0;
@endphp
@foreach($derives as $derive)
    @if($row != 0)
        <hr>
    @else
        <div class="row form-group">
            <div class="col-6 col-md-8">
                <label for="derives">Dérivés </label>
                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
            </div>
            <div class="col-md-4 col-6">
                <label for="derives">Quantité</label>
                <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
            </div>
        </div>
    @endif
        <div class="row form-group">
            <div class="col-6 col-md-8">
                <label for="{{'derive_'.$derive->id}}">
                    <span class="text-dark font-weight-bolder">{{ucfirst($derive->nom)}}</span>
                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                </label>
            </div>
            <div class="form-group col-md-4 col-6" style="margin-bottom: 0;">
                <div class="input-group mx-auto">
                    <input type="number" data-id="{{$derive->id}}" class="qantite form-control form-control-sm text-center" value="{{ old('derive_'.$derive->id) == null ? 0 : old('derive_'.$derive->id) }}" name="{{'derive_'.$derive->id}}" id="{{'derive_'.$derive->id}}" required/>
                </div>
            </div>
        </div>
    @php
        $row ++;
    @endphp
@endforeach
@if($derives->count() == 0 && !in_array(0,$table_data))
<div class="row form-group">
    <div class="col-12 text-center">
        <span class="text-danger font-weight-bolder">Attention !</span>
        <span class="text-dark">Aucune Information correspondante...</span>
    </div>
</div>
@endif
@if(in_array(0,$table_data))
<div class="row form-group">
    <div class="col-12 text-center">
        <span class="text-danger font-weight-bolder">Attention !</span>
        <span class="text-dark">Veuillez choisir un produit...</span>
    </div>
</div>
@endif
<div class="row form-group">
    <div class="form-group col-6">
        <input type="hidden" id="ids" name="ids" class="form-control" readonly>
    </div>
    <div class="form-group col-6">
            <input type="hidden" id="valeurs" name="valeurs" class="form-control" readonly>
    </div>
</div>