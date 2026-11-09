<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('app-assets/css/pages/dashboard-ecommerce.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e('Farm App Home'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('menu'); ?>
    <?php echo $__env->make('admin/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenu'); ?>
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Dashboard Ecommerce Starts -->
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <!-- Medal Card -->
                        <div class="col-xl-4 col-lg-6 col-sm-6 col-12">
                            <div class="card card-congratulation-medal">
                                <div class="card-body">
                                    <h4 class="text-center">Bandes en Cours</h4>
                                    <h2 class=" mt-2 ">
                                        <div class="divider mb-2">
                                            <div class="divider-text text-uppercase">
                                                <h1 class="mx-1">
                                                    <?php echo e($bandes_en_cours->count()); ?>

                                                </h1>
                                            </div>
                                        </div>
                                    </h2>
                                    <div class="text-center">
                                        <a href="<?php echo e(route('bande.index')); ?>" type="button" class="btn btn-secondary btn-gradient-secondary">Toutes les bandes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Medal Card -->

                        <!-- Statistics Card -->
                        <div class="col-xl-8 col-sm-6 col-lg-6 col-12">
                            <div class="card card-statistics">
                                <div class="card-header">
                                    <h4 class="card-title">Statistics</h4>
                                    <div class="d-flex align-items-center">
                                        <p class="card-text font-small-2 mr-25 mb-0">Mis à jour le  <?php echo e(date('d-M-Y à H:i:s')); ?></p>
                                    </div>
                                </div>
                                <div class="card-body statistics-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-6 col-sm-7 col-12 mb-2 mx-auto">
                                            <div class="media">
                                                <div class="avatar sbg-light-secondary mr-2">
                                                    <a class="avatar-content text-secondary" title="Plus d'informations" href="<?php echo e(route('batiment.index')); ?>">
                                                        <i data-feather="home" class="avatar-icon"></i>
                                                    </a>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder text-center mb-0"><?php echo e($batiments->count()); ?></h4>
                                                    <p class="card-text font-small-3 text-center mb-0">Batiments</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-6 col-sm-7 mb-2 col-12 mx-auto">
                                            <?php
                                                $function_matieres_premiere = Matierepremiere($matiere_premieres);
                                            ?>
                                            <div class="media">
                                                <div class="avatar bg-light-danger mr-2">
                                                    <div class="avatar-content" title="Plus d'informations" data-toggle="modal" data-target="#informations" onclick="response_ajax('result_informations','<?php echo e($function_matieres_premiere['ids_pas_en_stock']); ?>','<?php echo e(route('matiere_premiere.index')); ?>','GET')">
                                                        <i data-feather="shield-off" class="avatar-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="media-body my-auto">
                                                    <h4 class="font-weight-bolder text-center mb-0"><?php echo e($function_matieres_premiere['pas_en_stock']); ?></h4>
                                                    <p class="card-text font-small-3 text-center mb-0">Matières Prémières
                                                        <div class="font-small-1 text-center"> Pas en stock </din>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Statistics Card -->
                    </div>
                
                </section>
                <!-- Dashboard Ecommerce ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- Modale info-->
    <div class="modal fade" id="informations" tabindex="-1" aria-labelledby="informationsTitle" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="informationsTitle"> Plus d'informations </h5>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mx-auto" id="result_informations">
                            Aucune information disponible!!!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Modale info-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('app-assets/js/scripts/pages/dashboard-ecommerce.js')); ?>"></script>
    <script type="text/javascript">
        document.querySelector('#Acceuil').classList.add('active');
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/home/home.blade.php ENDPATH**/ ?>