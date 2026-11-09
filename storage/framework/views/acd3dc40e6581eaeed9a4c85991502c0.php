<?php $__env->startSection('title'); ?>
<?php echo e('Toute les caisses'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('menu'); ?>
    <?php echo $__env->make('admin.menu.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                            <h2 class="content-header-title float-left mb-0"><?php echo e(__('Liste des Comptes')); ?></h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Acceuil')); ?></a></li>
                                    <li class="breadcrumb-item active"><?php echo e(__('Liste des Comptes')); ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card pl-1 pr-1">
                <div class="content-body">
                    <div class="row bg-light-success">
                        <div class="col-12">
                            <a class="btn btn-success btn-gradient-success m-1" href="<?php echo e(route('compte.create')); ?>">
                                <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>

                <!-- Table without card start -->
	                <div class="row" id="table-without-card">
					    <div class="table-responsive" id="results">
					        <table class="table">
					            <thead>
					                <tr class="text-center">
					                    <th>N°</th>
					                    <th><?php echo e(__('Nom du compte')); ?></th>
					                    <th><?php echo e(__('Type de compte')); ?></th>
					                    <th><?php echo e(__('Nom de la banque')); ?></th>
					                    <th><?php echo e(__('Solde initial')); ?></th>
					                    <th><?php echo e(__('Solde actuel')); ?></th>
					                    <th><?php echo e(__('Action')); ?></th>
					                </tr>
					            </thead>
					            <tbody>
					                <?php $__currentLoopData = $comptes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					                    <tr class="text-center">
					                        <?php
					                            $count = 20 * $comptes->currentPage() - 19 + $loop->index;
					                        ?>
					                        <td><?php echo e($count); ?></td>
					                        <td>
					                            <span class="font-weight-bolder"><?php echo e(ucfirst($compte->nom ?? 'Aucun nom de compte')); ?></span>
					                        </td>
					                        <td>
					                            <span class="text-center text-dark font-weight-bolder">
					                                <?php echo e(ucfirst($compte->type_account)); ?>

					                            </span>
					                        </td>
					                        <td>
					                            <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3">
					                                <?php echo e(ucfirst($compte->nom_banque ?? __('Aucune banque'))); ?>

					                            </span>
					                        </td>
					                        <td>
					                            <span class="text-center text-dark font-weight-bolder">
					                                <?php echo e(number_format($compte->initial_balance, 0, ',', ',')); ?> FCFA
					                            </span>
					                        </td>
					                        <td>
					                            <span class="text-center text-dark font-weight-bolder">
					                                <?php echo e($compte->current_balance ? number_format($compte->current_balance, 0, ',', ',') : '-'); ?> FCFA

					                            </span>
					                        </td>
					                        <td>
					                            <div>
					                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
					                                    <i data-feather="more-vertical"></i>
					                                </button>
					                                <div class="dropdown-menu">
					                                    <span class="dropdown-item" onclick="actions('form_delete_<?php echo e($compte->id); ?>', '<?php echo e(__('Effacer')); ?>')">
					                                        <i class="text-danger" data-feather='trash'></i>
					                                        <span class="ml-1"><?php echo e(__('Effacer')); ?></span>
					                                        <form id="form_delete_<?php echo e($compte->id); ?>" action="<?php echo e(route('compte.destroy', $compte->id)); ?>" method="POST" style="display: none;">
					                                            <?php echo csrf_field(); ?>
					                                            <?php echo method_field('DELETE'); ?>
					                                        </form>
					                                    </span>
					                                    <a href="<?php echo e(route('compte.edit', $compte->id)); ?>" class="dropdown-item">
                                                        <i class="text-primary" data-feather='edit'></i>
                                                        <span class="ml-1">Modifier</span>
                                                    </a>
					                                </div>
					                            </div>
					                        </td>
					                    </tr>
					                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					            </tbody>
					        </table>
					    </div>
					</div>

                <?php if($comptes->hasPages()): ?>
                    <nav class="d-flex col-12 mt-1 justify-items-center justify-content-center">
                        <div class="row mx-auto justify-content-center flex-fill d-md-none">
                            <div class="mr-1 d-md-none">
                                <p class="small text-muted">
                                    <?php echo __('De'); ?>

                                    <span class="fw-semibold"><?php echo e($comptes->firstItem()); ?></span>
                                    <?php echo __('à'); ?>

                                    <span class="fw-semibold"><?php echo e($comptes->lastItem()); ?></span>
                                    <?php echo __('sur'); ?>

                                    <span class="fw-semibold"><?php echo e($comptes->total()); ?></span>
                                    <?php echo __('resultats'); ?>

                                </p>
                            </div>
                            <ul class="pagination">
                                
                                <?php if($comptes->onFirstPage()): ?>
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">Précédent</span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre(<?php echo e($comptes->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                    </li>
                                <?php endif; ?>

                                
                                <?php if($comptes->hasMorePages()): ?>
                                    <li class="page-item">
                                        <a class="page-link" onclick="mettre(<?php echo e($comptes->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                    <span class="fw-semibold"><?php echo e($comptes->firstItem()); ?></span>
                                    <?php echo __('à'); ?>

                                    <span class="fw-semibold"><?php echo e($comptes->lastItem()); ?></span>
                                    <?php echo __('sur'); ?>

                                    <span class="fw-semibold"><?php echo e($comptes->total()); ?></span>
                                    <?php echo __('resultats'); ?>

                                </p>
                            </div>

                            <div>
                                <ul class="pagination">
                                    
                                    <?php if($comptes->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($comptes->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php $__currentLoopData = $comptes->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <?php if(is_string($element)): ?>
                                            <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                        <?php endif; ?>

                                        
                                        <?php if(is_array($element)): ?>
                                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($page == $comptes->currentPage()): ?>
                                                    <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                <?php else: ?>
                                                    <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    
                                    <?php if($comptes->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($comptes->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
    </div>
</div>
</div>
</div>
<div class="modal fade" id="description" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle">Ligne de stock</h5>
                <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10 mx-auto" id="info_description">
                        Une ligne D'ajout de stock s'affichera ici
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="btn px-2 btn-success btn-gradient-success mx-auto">
                    <i data-feather='check' style="width: 25px;height: 25px;"></i>
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
    document.getElementById('CaisseListe').classList.add('active');
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.templates.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/Modules/GestionCaisse/Resources/views/compte/index.blade.php ENDPATH**/ ?>