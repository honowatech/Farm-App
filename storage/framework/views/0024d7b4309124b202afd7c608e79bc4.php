<?php $__env->startSection('menu'); ?>
    <?php echo $__env->make('admin.menu.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
<?php echo e('Liste des types de dépense'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenu'); ?>
<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0"><?php echo e(__('depenses::translated.Liste des catégories de dépense')); ?></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo e(route('home')); ?>"><?php echo e(__('depenses::translated.Acceuil')); ?></a>
                                </li>
                                <li class="breadcrumb-item active"><?php echo e(__('depenses::translated.Liste des catégories de dépense')); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card pl-1 pr-1">
            <div class="content-body">
                <div class="row justify-content-between bg-light-secondary" >
                    <a class="btn btn-secondary btn-gradient-secondary m-1" href="<?php echo e(route('categorie.create')); ?>">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form action="<?php echo e(route('categorie.index')); ?>" class="col-lg-3 mt-1" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend ">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="<?php echo e(session()->get('search')); ?>" placeholder="Rechercher une catégories">
                                </div>
                            </div>
                            <input type="hidden" name="page" id="page" value="">
                        </form>
                </div>
            </div>
            <!-- Table without card start -->
                <div class="row" id="table-without-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th>N°</th>
                                    <th><?php echo e(__('depenses::translated.Nom')); ?></th>
                                    <th><?php echo e(__('depenses::translated.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $count = 20 * $categories->currentPage() - 19;
                                ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="text-center">
                                        <td>
                                            <?php echo e($count); ?>

                                            <?php $count++ ?>
                                        </td>
                                        <td>
                                            <span class="font-weight-bolder"><?php echo e(ucfirst($categorie->nom)); ?></span>
                                        </td>
                                        <td>
                                            <div>
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                    <i data-feather="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" onclick="response_ajax('info_description',['<?php echo e($categorie->id); ?>'],'<?php echo e(route('categorie.show', $categorie->id)); ?>','GET')" data-toggle="modal" data-target="#description">
                                                        <i data-feather='eye'></i>
                                                        <span class="ml-1"><?php echo e(__('depenses::translated.Informations')); ?></span>
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo e(route('categorie.edit', $categorie->id)); ?>">
                                                        <i data-feather="edit-2"></i>
                                                        <span class="ml-1"><?php echo e(__('depenses::translated.Editer')); ?></span>
                                                    </a>
                                                    <span class="dropdown-item" onclick="actions('form_delete_<?php echo e($categorie->id); ?>', '<?php echo e(__('depenses::translated.Supprimer')); ?>', '<?php echo e($categorie->nom); ?>')">
                                                        <i class="text-danger" data-feather='trash'></i>
                                                        <span class="ml-1"><?php echo e(__('depenses::translated.Supprimer')); ?></span>
                                                        <form id="form_delete_<?php echo e($categorie->id); ?>" action="<?php echo e(route('categorie.destroy', $categorie->id)); ?>" method="POST" style="display: none;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if($categories->hasPages()): ?>
                    <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                        <div class="row mx-auto justify-content-center flex-fill d-md-none">
                            <div class="mr-1 d-md-none">
                                <p class="small text-muted">
                                    <?php echo __('De'); ?>

                                    <span class="fw-semibold"><?php echo e($categories->firstItem()); ?></span>
                                    <?php echo __('à'); ?>

                                    <span class="fw-semibold"><?php echo e($categories->lastItem()); ?></span>
                                    <?php echo __('sur'); ?>

                                    <span class="fw-semibold"><?php echo e($categories->total()); ?></span>
                                    <?php echo __('resultats'); ?>

                                </p>
                            </div>
                            <ul class="pagination">
                                
                                <?php if($categories->onFirstPage()): ?>
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">Précédent</span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre(<?php echo e($categories->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                    </li>
                                <?php endif; ?>

                                
                                <?php if($categories->hasMorePages()): ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre(<?php echo e($categories->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link"><?php echo app('translator')->get('pagination.next'); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="d-none flex-md-fill d-md-flex align-items-md-center justify-content-md-center">
                            <div class="mr-1">
                                <p class="small text-muted">
                                    <?php echo __('vu de '); ?>

                                    <span class="fw-semibold"><?php echo e($categories->firstItem()); ?></span>
                                    <?php echo __('à'); ?>

                                    <span class="fw-semibold"><?php echo e($categories->lastItem()); ?></span>
                                    <?php echo __('sur'); ?>

                                    <span class="fw-semibold"><?php echo e($categories->total()); ?></span>
                                    <?php echo __('resultats'); ?>

                                </p>
                            </div>

                            <div>
                                <ul class="pagination">
                                    
                                    <?php if($categories->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($categories->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php $__currentLoopData = $categories->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <?php if(is_string($element)): ?>
                                            <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                        <?php endif; ?>

                                        
                                        <?php if(is_array($element)): ?>
                                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($page == $categories->currentPage()): ?>
                                                    <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                <?php else: ?>
                                                    <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    
                                    <?php if($categories->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($categories->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </nav>
                <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto" id="info_description">
                        Infos de chaque Batîment ici !
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    Ok
                </button>
            </div>
        </div>
    </div>
</div>
<div id="button_footer" hidden>
    <button type="button" class="col-md-5 col-10 mx-auto btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
    <form method="POST" class="destroy_form m-0 p-0 col-md-5 col-10 mx-auto">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button type="submit" class="col-12 btn btn-gradient-success btn-success">Confirmer</button>
    </form>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
		document.querySelector('#CategorieListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/Modules/Depenses/Resources/views/categorie/index.blade.php ENDPATH**/ ?>