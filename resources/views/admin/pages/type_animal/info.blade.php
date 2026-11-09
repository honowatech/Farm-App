<div class="mb-1"> 
    <h5 class="text-uppercase font-weight-bolder text-center">Nom : </h5>
    <div class="divider my-2">
        <div class="divider-text text-uppercase">
            {{$type_animal->nom}}
        </div>
    </div>
    <h5 class="text-uppercase font-weight-bolder text-center">Description : </h5>
        @if ( textBreack($type_animal->description, 60) === true )
            <p class=" text-center">{{$type_animal->description}}</p>  
        @else 
            <p>{{textBreack($type_animal->description, 60)}}</p>
        @endif
</div>
