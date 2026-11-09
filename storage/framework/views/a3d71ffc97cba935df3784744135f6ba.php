<?php $__env->startSection('title'); ?>
Infos sur <?php echo e(ucfirst($produit->nom)); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('menu'); ?>
    <?php
        $role = auth()->user()->type_user->libelle;
    ?>
    <?php echo $__env->make($role.'.menu.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenu'); ?>
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-12 col-12">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-left mb-0 content-header-title">Infos sur <?php echo e(ucfirst($produit->nom)); ?></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('produit.index')); ?>">Liste des produits</a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur <?php echo e(ucfirst($produit->nom)); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-1 card">
            <div class="content-body">
                <!-- app e-commerce details start -->
                <section class="app-ecommerce-details">
                        <!-- Product Details starts -->
                        <div class="card-body">
                            <div class="my-2 row">
                                <div class="mb-2 col-12 col-md-5 d-flex align-items-center justify-content-center mb-md-0">
                                    <div class="col-10 d-flex align-items-center justify-content-center d-none">
                                        <i data-feather='shopping-bag' class="w-50 h-50"></i>
                                    </div>
                                </div>
                                <div class="mx-auto col-12 col-md-7">
                                    <h1><?php echo e(ucfirst($produit->nom)); ?></h1>
                                    <span class="card-text item-company">
                                        Produit par
                                        <a href="<?php echo e(route('home')); ?>" class="company-name">Farm App</a>
                                    </span>
                                    <div class="mt-1 card-text">
                                        <span class="badge badge-pill badge-glow <?php if($produit->statut == 0): ?> badge-danger <?php else: ?> badge-success <?php endif; ?>">
                                        <?php if($produit->statut == 0): ?>
                                            Invisible
                                        <?php else: ?>
                                            Visible
                                        <?php endif; ?>
                                        </span>
                                        <span class="px-1">-</span>
                                        <span class="text-<?php echo e(Stockproduit($produit)['valeur_en_stock'] > 0 ? 'success' : 'danger'); ?> font-weight-bolder">
                                            <?php echo e(Stockproduit($produit)['statut_stock']); ?> (<?php echo e(Stockproduit($produit)['valeur_en_stock']); ?>)
                                        </span></p>
                                        <hr>
                                        <h5>Description:</h5>
                                        <p class="card-text">
                                        <?php echo e($produit->description); ?>....
                                    </div>
                                    <hr/>
                                    <div class="product-color-options">
                                        <h5>Variétés</h5>
                                    <?php if($produit->derives->count() != 0): ?>
                                        <ul class="mb-0 list-unstyled">
                                        <?php $__currentLoopData = $produit->derives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $derive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="d-inline-block selected">
                                                <div class="color-option b-primary">
                                                    <div class="filloption">
                                                        <a href="<?php echo e(route('derive_produit.show',$derive->id)); ?>" style="text-decoration: underline;">
                                                            <?php echo e($derive->nom); ?> </a> <b>,</b>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    <?php else: ?>
                                        <i data-feather='slash' class="text-danger"></i>
                                    <?php endif; ?>
                                    </div>
                                    <hr/>
                                    <div class="pt-1 row">
                                        <form action="<?php echo e(route('derive_produit.create')); ?>" method="GET">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="produit" value="<?php echo e($produit->id); ?>">
                                        <button type="submit" class="mr-1 btn btn-secondary">
                                            <i data-feather='share-2' class="mr-50"></i>
                                            <span>Ajouter des dérivées</span>
                                        </button>
                                        </form>
                                        <a href="<?php echo e(route('produit.edit',$produit->id)); ?>" title="Modifier" type="button" class="mr-1 btn btn-icon btn-secondary waves-effect">
                                            <i data-feather='edit-2'></i>
                                        </a>
                                        <button <?php if($produit->statut == 1): ?> title="Rendre Invisible" <?php else: ?> title="Rendre Visible" <?php endif; ?> class="btn btn-icon mr-1 waves-effect <?php if($produit->statut == 1): ?> btn-danger <?php else: ?> btn-success <?php endif; ?> " onclick="actions('form_active_<?php echo e($produit->id); ?>', <?php if($produit->statut == 1): ?> 'Rendre Invisible' <?php else: ?> 'Rendre Visible' <?php endif; ?> ,'<?php echo e($produit->nom); ?>')">
                                            <?php if($produit->statut == 1): ?>
                                                <i data-feather='eye-off'></i>
                                            <?php else: ?>
                                                <i data-feather='eye'></i>
                                            <?php endif; ?>
                                            <form id="form_active_<?php echo e($produit->id); ?>" action="<?php echo e(route('produit.destroy',$produit->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        </button>
                                    </div>
                                    <?php if($produit->derives->count() == 0): ?>
                                        <span class="text-danger">Attention !</span> <small class="text-muted">Enregistrer au moins une dérivée pour gérer les stocks</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Product Details ends -->
                </section>
                <!-- app e-commerce details end -->
            </div>
    </div>
    <div class="py-1 card">
            <div class="content-body">
                <section>
                        <!-- Related Products starts -->
                        <div class="card-body">
                            <div class="mt-2 text-center">
                                <h4>Produits Dérivés</h4>
                                <p class="card-text">
                                <?php if($produit->derives->count() == 0): ?>
                                    <i class="text-danger" data-feather='alert-circle'></i> Aucune information disponible...
                                <?php else: ?>
                                    Ce sont les variantes de <?php echo e(ucfirst($produit->nom)); ?>

                                <?php endif; ?>

                                </p>
                            </div>
                        <?php if($produit->derives->count() != 0): ?>
                            <div class="px-4 py-2 swiper-responsive-breakpoints swiper-container">
                                <div class="swiper-wrapper">
                                    <?php $__currentLoopData = $produit->derives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $derivee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="py-1 swiper-slide">
                                        <a href="<?php echo e(route('derive_produit.show',$derivee->id)); ?>">
                                            <div class="text-center item-heading">
                                                <h4 class="mb-0 text-truncate">
                                                    <?php echo e(ucfirst($produit->nom)); ?>_<?php echo e(ucfirst($derivee->nom)); ?>

                                                </h4>
                                                <small class="text-body">
                                                    <?php if($derivee->min != $derivee->max): ?>
                                                        ( <?php echo e($derivee->min); ?> - <?php echo e($derivee->max); ?> )
                                                    <?php else: ?>
                                                        <?php echo e($derivee->min); ?>

                                                    <?php endif; ?>
                                                    <?php echo e($derivee->unite->abrege); ?>

                                                </small>
                                                <span class="text-<?php echo e(StockDerive($derivee)['valeur_en_stock'] > 0 ? 'success' : 'danger'); ?> font-weight-bolder">
                                                    <?php echo e(StockDerive($derivee)['statut_stock']); ?> (<?php echo e(StockDerive($derivee)['valeur_en_stock']); ?>)
                                                </span>
                                                </div>
                                            <div class="mx-auto text-center img-container w-50 py-50">
                                                <i data-feather='bookmark' class="w-50 h-25 text-secondary"></i>
                                            </div>
                                            <div class="mt-1 text-center item-meta">
                                                <span class="badge badge-pill badge-glow <?php if($derivee->statut == 0): ?> badge-danger <?php else: ?> badge-success <?php endif; ?>">
                                                <?php if($derivee->statut == 0): ?>
                                                    Invisible
                                                <?php else: ?>
                                                    Visible
                                                <?php endif; ?>
                                                </span>
                                                <h5 class="m-1 card-text text-dark"><?php echo e($derivee->prix_unitaire); ?> FCFA</h5>
                                            </div>
                                        </a>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <!-- Add Arrows -->
                                <div class="swiper-button-next text-dark"></div>
                                <div class="swiper-button-prev text-dark"></div>
                            </div>
                        <?php endif; ?>
                        </div>
                        <!-- Related Products ends -->
                </section>
            </div>
    </div>
</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script src="<?php echo e(asset('app-assets/vendors/js/ui/jquery.sticky.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/extensions/swiper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/vendors/js/extensions/toastr.min.js')); ?>"></script>

    <script src="<?php echo e(asset('app-assets/js/scripts/pages/app-ecommerce-details.js')); ?>"></script>
    <script src="<?php echo e(asset('app-assets/js/scripts/forms/form-number-input.js')); ?>"></script>
    <script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
        document.querySelector('#ProduitListe').classList.add('active');
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/produit/info.blade.php ENDPATH**/ ?>