<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1, minimal-ui">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="apple-touch-icon" href="{{ logo() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ logo() }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Vendors CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/animate/animate.min.css') }}">

    {{-- Datatables --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') }}">

    {{-- Pickers & calendrier --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/calendars/fullcalendar.min.css') }}">

    {{-- Theme CSS (Bootstrap 4 / Vuexy) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">

    {{-- Plugins & pages --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-number-input.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-user.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/app-calendar.css') }}">
    @yield('css')
</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern bordered-layout footer-sticky pace-done footer-fixed footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="">
    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);" aria-label="Ouvrir le menu"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item pr-1">
                    @php $alertesCount = app(\App\Services\AlerteService::class)->count(); @endphp
                    <a class="nav-link" href="{{ route('alerte.index') }}" title="Alertes" aria-label="Alertes">
                        <i data-feather="bell"></i>
                        @if($alertesCount > 0)
                            <span class="badge badge-danger badge-pill ml-25">{{ $alertesCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-user pr-1">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name font-weight-bolder"> {{ auth()->user()->name }} </span>
                            <span class="user-status">{{auth()->user()->type_user->libelle}}</span>
                        </div>
                        <span class="avatar" style="padding: 0.2rem;">
                          <i data-feather="user" class="text-dark" style="height: 30px; width: 30px;"></i>
                          <span class="avatar-status-online"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{route('home')}}">
                            <i class="mr-50" data-feather="settings"></i> Settings
                        </a>
                        <a class="dropdown-item" href="{{route('home')}}">
                            <i class="mr-50" data-feather="help-circle"></i> FAQ
                        </a>
                        <a class="dropdown-item" onclick="deconnecter()"><i class="mr-50 text-danger" data-feather="power"></i> Déconnexion </a>
                        <form id="log_out" action="{{route('logout')}}" method="POST">
                          @csrf
                        </form>
                    </div>
                </li>
                    <li class="nav-item dropdown dropdown-language">
                        <a class="nav-link dropdown-toggle" id="dropdown-flag" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(app()->getLocale() == 'fr')
                                <i class="flag-icon flag-icon-fr"></i><span class="selected-language">Français</span>
                            @else
                                <i class="flag-icon flag-icon-us"></i><span class="selected-language">English</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                            @if(app()->getLocale() == 'fr')
                                 <!-- Anglais -->
                                <a class="dropdown-item" onclick="SoumissionFrenchForm()">
                                    <i class="flag-icon flag-icon-us"></i> English
                                </a>
                             @else
                             <!-- Français -->
                            <a class="dropdown-item" onclick="SoumissionEnglishForm()">
                                <i class="flag-icon flag-icon-fr"></i> Français
                            </a>
                             @endif
                        </div>
                        <form id="monFormulaireEn" action="{{route('lang.switch','en')}}" method="POST">
                                        @csrf
                        </form>
                        <form id="monFormulaireFr" action="{{route('lang.switch','fr')}}" method="POST">
                                        @csrf
                        </form>
                    </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->
    @include(auth()->user()->type_user?->libelle . '.menu.menu')
    <!-- BEGIN: Content-->
    @yield('contenu')
    <!-- END: Content-->
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>
    <div class="text-center" hidden id="spinner">
        <div class="col-12 text-center">
            <div class="spinner-border text-dark" role="status">
                <span class="sr-only"></span>
           </div>
       </div>
    </div>
    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-left d-block d-md-inline-block mt-25">
                <span class="d-none d-sm-inline-block">COPYRIGHT</span> &copy; 2023
                <a class="ml-25" href="https://honowa.com" target="_blank">Honowa Technologies</a>
                <span class="d-none d-sm-inline-block">, Tous les droits reservés</span>
            </span>
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button" aria-label="Retour en haut"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-number-input.js') }}"></script>
    <!-- END: Page JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-input-mask.js') }}"></script>
    <script src="{{asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/extensions/ext-component-sweet-alerts.js')}}"></script>
    <!-- END: Theme JS-->
    <script>
        cliquer = 1;
            function response_ajax(id,data,lien,method,put){
                var result = document.querySelector('#'+id),
                    spinner = document.querySelector('#spinner').innerHTML;
                    if(put == undefined){
                        result.innerHTML = spinner;
                    }else{
                        if (document.querySelector('#tospin')) {
                            document.querySelector('#tospin').innerHTML = spinner;
                        }else{
                            result.innerHTML = "<option value=''>Chargement...</option>"
                        }
                    }
                jQuery.ajax({
                    url: lien,
                    type : method,
                    data : { table_data : data , '_token' : "{{ csrf_token() }}" },
                    success: function(response)
                    {
                        result.innerHTML = response;
                        if (document.querySelector('#tospin')) {
                            document.querySelector('#tospin').innerHTML = '';
                        }
                    },
                    error: function(){
                        if(put == undefined){
                            result.innerHTML = "<h4 class='text-danger text-center'> Erreur !</h4><div class='w-100 text-center'>Un problème est survenu veuillez atualiser la page !!! </div>";
                        }else{
                            if (document.querySelector('#tospin')) {
                                document.querySelector('#tospin').innerHTML = "<h4 class='text-danger text-center'> Erreur !</h4><div class='w-100 text-center'>Un problème est survenu veuillez atualiser la page !!! </div>";
                            }else{
                                result.innerHTML = "<option value=''>Erreur! Veuillez actualisez la page...</option>"
                            }
                        }
                    }
                });

            }
            function remplir_montant(id){
                var montant2 = document.querySelector('#'+id+'2')
                    montant = document.querySelector('#'+id).value.replaceAll(',','');
                montant2.value = montant.replaceAll(',','')
            }
            function format_montant(id) {
                number = document.querySelector('#'+id).value.replaceAll(' ','');
                numero = 0
                number= number.replaceAll(',', "")
                number=parseInt(number);
                if (isNaN(number)) {
                    document.getElementById(id).value = '';
                }else{
                number =  number.toLocaleString('fr-FR', {
                        groupSize: 3,
                        useGrouping: true,
                    });
                document.getElementById(id).value =  number.replaceAll(/\s/g, ",")
                }
                remplir_montant(id);
            }
            function call_ajax(){
                var valeur = document.querySelector('#id_produit').value,
                    id_produit = valeur == '' ? 0 : valeur,
                    result = document.querySelector('#result_ajax');
                response_ajax('result_ajax',[id_produit],'{{route('ajax_result_derive_ramassage')}}','GET');
            }
            function  phone(id){
                var phone_number2 = document.querySelector('#'+id+'2')
                    phone_number = document.querySelector('#'+id).value.replaceAll(' ','');

                if (isNaN(phone_number)) {
                    phone_number2.value = '';
                    document.querySelector('#'+id).value = isNaN(parseInt(phone_number)) ? '' : parseInt(phone_number);
                    phone(id);
                }else{
                    if(parseInt(phone_number).toString().length == 9 && phone_number[0] == '6'){
                        document.querySelector('#'+id).value = phone_number[0]+' '+phone_number[1]+''+phone_number[2]+' '+phone_number[3]+''+phone_number[4]+' '+phone_number[5]+''+phone_number[6]+' '+phone_number[7]+''+phone_number[8]
                    }else{
                        document.querySelector('#'+id).value = phone_number.replaceAll(' ','')
                    }
                    phone_number2.value = document.querySelector('#'+id).value.replaceAll(' ','');
                }
            }
            function recapituler(type,identifiant){
                var topass = false,
                    inputs = document.querySelectorAll("input"),
                    selects = document.querySelectorAll("select"),
                    textareas = document.querySelectorAll("textarea"),
                    timer = 2500;
                    datas = {};
                    inputs.forEach((input) => {
                        if (input.value.trim() == '' && topass == false && input.required) {
                            if(input.classList.contains("flatpickr-human-friendly")){
                                input.nextElementSibling.focus()
                                input.nextElementSibling.click();
                            }else{
                                input.focus();
                            }
                            document.querySelector('#'+input.id+'_error').hidden = false;
                            setTimeout(function(){
                                document.querySelector('#'+input.id+'_error').hidden = true;
                            },timer)
                            topass = true;
                        }
                        if (input.id != ''){
                            if (input.required || input.classList.contains("optionnel")) {
                                datas[input.id] = input.value;
                            }
                        }
                    });
                    selects.forEach((select) => {
                        if (select.value.trim() == '' && topass == false && select.required) {
                            select.focus();
                            document.querySelector('#'+select.id+'_error').hidden = false;
                            setTimeout(function(){
                                document.querySelector('#'+select.id+'_error').hidden = true;
                            },timer)
                            topass = true;

                        }
                        if (select.required || select.classList.contains("optionnel")){
                            options = document.querySelectorAll('.'+select.id)
                            options.forEach((option) => {
                                if (option.selected) {
                                    datas[select.id] = option.innerHTML;
                                }
                            });
                        }
                    });
                    textareas.forEach((textarea) => {
                        if (textarea.value.trim() == '' && topass == false && textarea.required) {
                            textarea.focus();
                            document.querySelector('#'+textarea.id+'_error').hidden = false;
                            setTimeout(function(){
                                document.querySelector('#'+textarea.id+'_error').hidden = true;
                            },timer)
                            topass = true;
                        }
                        if (textarea.required || textarea.classList.contains("optionnel")){
                            datas[textarea.id] = textarea.value;
                        }
                    });
                if (topass == false) {
                    if (type == 'modal') {
                        $('#'+identifiant).modal()
                    }else if (type == 'button') {
                        document.querySelector('#'+identifiant).click()
                    }
                }
            }
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            @if (session('message'))
                toastr.success(@json(session('message')), 'Succès', { closeButton: true, tapToDismiss: true });
            @endif
            @if (session('error'))
                toastr.error(@json(session('error')), 'Erreur', { closeButton: true, tapToDismiss: true });
            @endif
            @if (session('warning'))
                toastr.warning(@json(session('warning')), 'Attention', { closeButton: true, tapToDismiss: true });
            @endif
            @if (session('info'))
                toastr.info(@json(session('info')), 'Info', { closeButton: true, tapToDismiss: true });
            @endif
            @if ($errors->any())
                toastr.error(@json($errors->first()), 'Erreur de validation', { closeButton: true, tapToDismiss: true });
            @endif
        })
        function escapeHtml(text) {
            return String(text).replace(/[&<>"']/g, function (m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
            });
        }
        function confirmation(message = 'Confirmer ?', id_form = undefined, confirmText = 'Oui, confirmer', cancelText = 'Annuler') {
            Swal.fire({
                icon: 'warning',
                html: '<h4 class="text-center my-1">' + message + '</h4>',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false, // Ne pas déclencher l'action destructive via la touche Entrée
                confirmButtonText: feather.icons['check'].toSvg({ class: 'font-medium-2 mr-50' }) + '<span>' + confirmText + '</span>',
                cancelButtonText: feather.icons['x'].toSvg({ class: 'font-medium-2 mr-50' }) + '<span>' + cancelText + '</span>',
                allowOutsideClick: true,
                customClass: {
                    confirmButton: 'btn btn-success mx-auto my-1',
                    cancelButton: 'btn btn-danger mx-auto my-1'
                },
                showClass: {
                    popup: 'animate__animated animate__bounceIn'
                },
                buttonsStyling: false,
                preConfirm: () => true
            })
            .then((result) => {
                if (result.isConfirmed) {
                    if (id_form != undefined) {
                        $('#' + id_form).submit();
                    }
                }
            });
        }
        function deconnecter(){
            confirmation('Vous <strong>Déconnecter</strong> ?', 'log_out');
        }
        function actions(id_form, action, nom_cible = ''){
            var message = 'Vous voulez <strong>' + escapeHtml(action) + ' ' + escapeHtml(nom_cible) + '</strong> ?';
            confirmation(message, id_form);
        }
        function insertView(view,id_modalMessage) {
            $('.'+id_modalMessage).each(function(){
                $(this).html(view);
            });
        }
        function STForm(id_modalMessage, route_fichier, methode, form = null, id_select = null, data = null) {
    let tab = ['POST', 'GET'];
    let formData = form ? $('#' + form).serialize() : '';

    if (tab.includes(methode)) {
        if (methode == 'GET') {
            $('#' + id_modalMessage).html('<div class="text-center mx-auto"><i class="fas fa-spinner fa-spin fa-3x font-10" role="status"></i> <br>Chargement...</div>');

            $.ajax({
                url: route_fichier,
                type: methode,
                data: { table_data: data, '_token': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    $('#' + id_modalMessage).html(response);
                },
                error: function() {
                    $('#' + id_modalMessage).html('<div class="text-center text-danger">Veuillez actualiser la page!!</div>');
                }
            });
        } else {
            $('#' + form).on('submit', function(e) {
                e.preventDefault();
                $('#' + id_modalMessage).html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-3x font-20" role="status"></i><br> Veuillez patienter...</div>');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: route_fichier,
                    type: methode,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#' + id_modalMessage).html('<div class="text-center text-success">Client ' + response.user.nom + ' enregistré avec succès</div>');
                            insertView(STForm(id_select, '{{ route('client') }}', 'GET'));
                        } else {
                            // Affichage des erreurs de validation sous chaque champ
                            $('#' + id_modalMessage).html('');
                            $.each(response.errors, function(field, errors) {
                                let inputField = $('[name="' + field + '"]');
                                inputField.after('<div class="alert alert-danger">' + errors.join('<br>') + '</div>');
                            });
                        }
                    },
                    error: function(xhr) {
                        // On récupère la réponse JSON qui contient les erreurs
                        if (xhr.status === 422) { // Vérifier si c'est une erreur de validation
                            let response = xhr.responseJSON;

                            // Vider l'ancien contenu du message et afficher à nouveau le formulaire
                            $('#' + id_modalMessage).html(STForm('cabrel', '{{ route('formulaire') }}', 'GET'));

                            setTimeout(function() {
                                // Remplir les champs avec les anciennes valeurs
                                $.each(response.old, function(field, value) {
                                    let inputField = $('[name="' + field + '"]');
                                    inputField.val(value);
                                });

                                // Afficher les erreurs sous les champs correspondants
                                $.each(response.errors, function(field, errors) {
                                    let inputField = $('[name="' + field + '"]');
                                    let inputGroup = inputField.closest('.input-group.input-group-merge');
                                    let errorMessage = $('<small class="alert alert-danger d-block mt-1">' + errors.join('<br>') + '</small>');
                                    inputGroup.after(errorMessage);

                                    // Disparition des erreurs après 3 secondes
                                    setTimeout(function() {
                                        errorMessage.fadeOut(500, function() {
                                            $(this).remove();
                                        });
                                    }, 3000);
                                });
                            }, 3000);
                        } else {
                            // Pour les autres erreurs, tu peux afficher une alerte ou un message dans la console
                            console.error('Erreur serveur :', xhr.responseText);
                        }
                    }
                });
            });
        }
    } else {
        $('#' + id_modalMessage).html('<div class="text-center text-danger"><b>Veuillez entrer une méthode valide!</b></div>');
    }
}

    function SoumissionFrenchForm() {
        // Soumet le formulaire pour changer la langue en anglais
        document.getElementById('monFormulaireEn').submit();
    }

    function SoumissionEnglishForm() {
        // Soumet le formulaire pour changer la langue en français
        document.getElementById('monFormulaireFr').submit();
    }

    </script>
    <script src="{{asset('app-assets/vendors/js/ui/jquery.sticky.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/form-repeater.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
    @yield('javascript')
    <script type="text/javascript">
        function close_modale(id){
            $('#'+id).modal('hide');
        }
        function valider(id){
            document.querySelector('#'+id).submit()
        }
    </script>
</body>
<!-- END: Body-->
</html>
