<div class="mb-1">        
        <div class="divider">
        <div class="divider-text">Agent</div>
        <div class="mt-2">
            {!! $liste !!}
        </div>
        </div>
        <div class="divider">
        <div class="divider-text">Verification</div>
        <div class="mt-2">
            @if($nettoyage->verif == 1) 
                <div class="badge badge-glow badge-success">
                    Oui
            @else
                <div class="badge badge-glow badge-danger">
                    Non
            @endif
            </div>
        </div>
        </div>
        <div class="divider">
        <div class="divider-text">Date</div>
        <div class="mt-2">
           <span class="badge badge-glow badge-secondary">Le {{ Ladate($nettoyage->date) }}</span> 
        </div>
        </div>

        <div class="divider">
        <div class="divider-text mb-1">Commentaire</div>
        @if($nettoyage->description == null)
              <p class=" text-center">Pas de commentaire ...</p>
        @else
            <div class="mt-2">
                @if ( textBreack($nettoyage->description, 60) === true )
              <p class=" text-center">{{$nettoyage->description}}</p>  
                @else 
               <p>{{textBreack($nettoyage->description, 60)}}</p>
                @endif
            </div>
        @endif
        
        </div>
</div>
