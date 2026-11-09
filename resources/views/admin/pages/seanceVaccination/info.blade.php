<div class="mb-1">
    <h4 class="text-uppercase font-weight-bolder text-center"> Description : </h4>  
            @if ( textBreack($seanceVaccination->description, 60) === true )
          <p class=" text-center">{{$seanceVaccination->description}}</p>  
            @else 
           <p>{{textBreack($seanceVaccination->description, 60)}}</p>
            @endif
</div>
