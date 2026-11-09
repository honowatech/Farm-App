<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>@yield('title')</title>
    <link rel="apple-touch-icon" href="{{asset('modules/gestioncaisse/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('modules/gestioncaisse/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

   <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/extensions/toastr.min.css') }}">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/forms/select/select2.min.css') }}">
    <!-- END: Vendor CSS-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- END: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/extensions/swiper.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/extensions/toastr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/css/core/menu/menu-types/horizontal-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/css/pages/app-ecommerce-details.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/css/plugins/forms/form-number-input.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/css/plugins/extensions/ext-component-toastr.css')}}">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/pages/app-user.css')}}">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/tables/datatable/dataTables.bootstrap4.min.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/tables/datatable/responsive.bootstrap4.min.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/tables/datatable/buttons.bootstrap4.min.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/pickers/flatpickr/flatpickr.min.css ')}}">
    <!-- END: Vendor CSS-->
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/bootstrap.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/bootstrap-extended.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/colors.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/components.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/themes/dark-layout.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/themes/bordered-layout.css ')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/css/plugins/forms/pickers/form-pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/gestioncaisse/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/animate/animate.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/extensions/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/css/plugins/forms/form-wizard.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('modules/gestioncaisse/vendors/css/forms/wizard/bs-stepper.min.css')}}">


    <!-- BEGIN: Custom CSS-->
    @yield('css')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static {{ Session::get('theme', 'light') == 'dark' ? 'dark-layout' : '' }}  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    <!--deconnexion-->
        <div class="modal fade" role="dialog" id="deconnexion" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;"  id="deconnexion" tabindex="-1">
      <div class="modal-dialog  modal-dialog-centered " >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Infos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
          <div class="modal-body text-center">
            deconnexion?
          </div>
          <div class="modal-footer">
            <div class="row  mx-auto">
              <div class="col-8">

                <button class="btn btn-danger" data-dismiss="modal">
                  <i class="" data-feather='x'></i>
                </button>
              </div>

              <div class="col-4">
                <a href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <button type="button" class="btn btn-success">
                        <i class="" data-feather='check'></i>
                    </button>

                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--fin-->

    <!-- BEGIN: Main Menu-->
        @yield('menu')
    <!-- END: Main Menu-->

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


   <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('modules/gestioncaisse/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('modules/gestioncaisse/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('modules/gestioncaisse/js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/js/scripts/forms/form-number-input.js') }}"></script>
    <!-- END: Page JS-->
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('modules/gestioncaisse/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/vendors/js/extensions/toastr.min.js') }}"></script>
    <!-- END: Page Vendor JS-->
    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('modules/gestioncaisse/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/js/core/app.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/vendors/js/forms/cleave/cleave.min.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('modules/gestioncaisse/js/scripts/forms/form-input-mask.js') }}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/js/scripts/extensions/ext-component-sweet-alerts.js')}}"></script>
    <!-- END: Theme JS-->
 <script src="{{asset('modules/gestioncaisse/vendors/js/ui/jquery.sticky.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/js/scripts/forms/form-repeater.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{asset('modules/gestioncaisse/js/scripts/forms/pickers/form-pickers.js')}}"></script>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
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
                        if (input.id != '') {
                            // Vérifier si l'élément est un checkbox
                            if (input.type === 'radio') {
                                // Ne récupérer la valeur que si le radio est coché
                                if (input.checked) {
                                    datas[input.id] = input.value;
                                }
                            } else {
                                // Pour tous les autres types d'inputs (comme les radios, textes, etc.)
                                if (input.required || input.classList.contains("optionnel")) {
                                    datas[input.id] = input.value;
                                }
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

            //fonction de copie (usage: ajoute juste la classe 'copie' à l'element que u veux cliqué et copier puis copie le code)
                var userText = $('.copied');
                // Ajouter un gestionnaire d'événement de clic à chaque élément
                userText.each(function(){
                    $(this).click(function () {
                        // Créer un élément input temporaire pour sélectionner et copier le texte
                        var tempInput = $('<input>');
                        $('body').append(tempInput);

                        // Mettre le texte de l'élément cliqué dans l'input temporaire
                        tempInput.val($(this).text()).select();

                        // Copier le texte sélectionné dans le presse-papiers
                        document.execCommand('copy');

                        // Supprimer l'input temporaire après la copie
                        tempInput.remove();

                        // Afficher un message de succès avec toastr
                        toastr['success']('', 'Copié avec succès');
                    });
                });
            //fin fonction
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
            @if(session()->has('message'))
                toastr['success'](
                    @json(session('message')),
                    'Succès',
                    {
                        closeButton: true,
                        tapToDismiss: true
                    }
                );
                @elseif(session()->has('error'))
                toastr['error'](
                    @json(session('error')),
                    'Erreur',
                    {
                        closeButton: true,
                        tapToDismiss: true
                    }
                );
            @endif

            $(document).ready(function() {
            // Détecter le clic sur le bouton pour basculer entre les thèmes
        });

    function confirmation(message = 'Confirmer ?',id_form = undefined) {
        Swal.fire({
            icon: 'warning',
            html: '<h4 class="text-center my-1">'+message+'<h4>',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: true, // Don't autofocus the confirm button
            confirmButtonText: feather.icons['check'].toSvg({ class: 'font-medium-2' }),
            cancelButtonText: feather.icons['x'].toSvg({ class: 'font-medium-2' }),
            allowOutsideClick: true,
            customClass: {
                confirmButton: 'btn btn-success mx-auto my-1',
                cancelButton: 'btn btn-danger mx-auto my-1'
            },
            showClass: {
                popup: 'animate__animated animate__bounceIn'
            },
            buttonsStyling: false,
            preConfirm: (result) => {
                return result.isConfirmed;
            }
        })
        .then((result) => {
            if (result.isConfirmed) {
                if (id_form != undefined) {
                    $('#'+id_form).submit();
                }
            }
        });
    }
    function deconnecter(){
                message = 'Vous <strong> Déconnecter </strong> ?';
                id_form = 'log_out';
                confirmation(message , id_form);
            }
    function actions(id_form, action, nom_cible = '') {
        let message = `Vous voulez <strong> ${action} ${nom_cible} </strong> ?`;
        confirmation(message, id_form);
    }

        function valider(id){
            document.querySelector('#'+id).submit()
        }

        function insertView(view,id_modalMessage) {
            $('.'+id_modalMessage).each(function(){
                $(this).html(view);
            });
        }
</script>
    @yield('script')
</body>
<!-- END: Body-->

</html>
