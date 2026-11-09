<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/pages/dashboard-ecommerce.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e('Farm App Home'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('menu'); ?>
    <?php
        $role = auth()->user()->type_user->libelle;
    ?>
    <?php echo $__env->make($role.'.menu.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenu'); ?>
    <!-- BEGIN: Content-->
    <div class="content app-content">
        <?php if(session()->has('modal')): ?>
            <?php if(session()->get('modal') !== 'logo modifier avec succès'): ?>
                <script type="text/javascript">
                      function ouvrirModaleAutomatique() {
                      // Simuler un clic sur le bouton d'ouverture de la modale
                      document.getElementById('modal_id').click();
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                      // Récupérer le message de la session Laravel
                      var reponse = "<?php echo e(session('modal')); ?>";

                      // Si la réponse contient la modale, ouvrir la modale automatiquement
                      if (reponse !== 'logo enregistré avec succès') {
                        ouvrirModaleAutomatique();
                      }
                    });
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="mb-2 content-header-left col-md-12 col-12">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                                <h2 class="float-left mb-0 content-header-title">Paramètres</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                                    </li>
                                    <li class="breadcrumb-item active">Paramètres
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section>
                    <div class="row">
                        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make($setting['path'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
                <!-- Dashboard Ecommerce ends -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- modal de gestion -->
    <div class="modal fade" id="setting" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id="modifiated">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionTitle">Boite de Gestion</h5>
                    <button type="button" class="m-0 bg-danger close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="p-1 col-12" id="info_gestion">
                            Le paramètre sélectionné s'affichera ici !!!
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- END: modal de gestion -->
    <!-- to confirm-->
    <div id="button_footer" hidden>
        <button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
        <form method="POST" class="p-0 m-0 mx-auto destroy_form col-md-5 col-10">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="col-12 btn btn-gradient-success btn-success">Confirmer</button>
        </form>
    </div>
    <!-- END: to confirm-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script type="text/javascript">
        document.querySelector('#Settings').classList.add('active');
        tableau_class = [
            'modal-sm',
            'modal-md',
            'modal-lg',
            'modal-xl',
        ];
        function classer(id,classe){
            var bloc = document.querySelector('#'+id);
            table = tableau_class
            table.push('classe');
            table.forEach((la_classe_1) => {
                bloc.classList.remove(la_classe_1);
            });
            bloc.classList.add(classe);
        }
        function savegarder(button,method,id_result,table_id,lien,refresh = false,modale=false,size_modale=false){
                var result = document.querySelector('#'+id_result),
                    button_click = document.querySelector('#'+button),
                    data=[],
                    lancer=true,
                    spinner = document.querySelector('#spinner').innerHTML;
            table_id.forEach((input) => {
                valeur = document.querySelector('#'+input);
                data.push(valeur.value);
                if (valeur.value.trim() == '' && lancer) {
                    valeur.classList.add('is-invalid');
                    valeur.focus();
                    lancer = false;
                }else if(valeur.value.trim() != ''){
                    valeur.classList.remove('is-invalid');
                }
            });
            if (!lancer) {
                return true;
            }
                button_click.disabled = true;
                result.innerHTML = spinner;
                jQuery.ajax({
                    url: lien,
                    type : method,
                    data : { table_data : data , '_token' : "<?php echo e(csrf_token()); ?>" },
                    success: function(response)
                    {
                        result.innerHTML = response;
                        button_click.disabled = false;
                        if (refresh != false && !response.includes('danger')) {
                            setTimeout(function (){
                                response_ajax(refresh[0],refresh[1],refresh[2],refresh[3])
                            }
                            ,1500)
                        }
                        if (size_modale != null && !response.includes('danger')) {
                            setTimeout(function (){
                                classer('modifiated',size_modale)
                            }
                            ,1550)
                        }
                        // console.log(response.includes('danger'))
                        if (modale != false && !response.includes('danger')) {
                            setTimeout(function (){
                                $('#'+modale).modal('hide')
                            }
                            ,1000)
                        }
                        if (response.includes('danger')) {
                            setTimeout(function (){
                                result.innerHTML = '';
                            }
                            ,2000)
                        }
                    },
                    error: function(){
                        button_click.disabled = false;
                        result.innerHTML = "<h4 class='text-center text-danger'> Erreur !</h4><div class='text-center w-100'>Un problème est survenu veuillez atualiser la page !!! </div>";

                    }
                });

        }




    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/setting/layout.blade.php ENDPATH**/ ?>