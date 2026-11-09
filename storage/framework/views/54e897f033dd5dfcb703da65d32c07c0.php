<?php $__env->startSection('title'); ?>
<?php echo e('Liste des fournisseurs '); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Liste des Fournisseurs</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Fournisseurs
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            </div>
    <div class="pr-1 pl-1 card">

            <div class="content-body">
                <div class="row justify-content-between bg-light-secondary" >
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('fournisseur.create')); ?>">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form action="<?php echo e(route('fournisseur.index')); ?>" class="mt-1 col-lg-3" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="<?php echo e(session()->get('search')); ?>" placeholder="Rechercher un fournisseur">
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
                                        <th>Noms Et Prénoms</th>
                                        <th>Telephone (s)</th>
                                        
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$fournisseurs->currentPage() -19 ;
                                        $table = ['secondary','danger','warning','info','primary','dark'];
                                	?>
                                	<?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	<?php echo e(name($fournisseur->nom)); ?>

	                                            </span>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	<?php echo e(phone($fournisseur->telephone)); ?>

                                                    <?php if($fournisseur->telephone_secondaire != null): ?>
                                                        / <?php echo e(phone($fournisseur->telephone_secondaire)); ?>

                                                    <?php endif; ?>
	                                            </span>
	                                        </td>
                                            
                                            <td>
                                                <span class="badge badge-pill badge-glow <?php if($fournisseur->statut == 0): ?> badge-danger <?php else: ?> badge-success <?php endif; ?>">
                                            		<?php if($fournisseur->statut == 0): ?>
		                                             	Désactivé
		                                            <?php else: ?>
		                                            	Actif
		                                            <?php endif; ?>
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" href="<?php echo e(route('fournisseur.show',$fournisseur->id)); ?>">
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1">Détails</span>
	                                                    </a>
                                                        <a class="dropdown-item" href="<?php echo e(route('fournisseur.edit',$fournisseur->id)); ?>">
                                                            <i data-feather="edit-2" class="mr-50"></i>
                                                            <span class="ml-1">Editer</span>
                                                        </a>
                                                        <span class="dropdown-item" onclick="actions('form_active_<?php echo e($fournisseur->id); ?>', <?php if($fournisseur->statut == 1): ?> 'Désactiver' <?php else: ?> 'Activer' <?php endif; ?> ,'<?php echo e($fournisseur->nom); ?>')">
                                                            <?php if($fournisseur->statut == 1): ?>
                                                                <i class="text-danger" data-feather='user-minus'></i>
                                                                <span class="ml-1" type="button">
                                                                    Désactiver
                                                                </span>
                                                            <?php else: ?>
                                                                <i class="text-success" data-feather='user-check'></i>
                                                                <span class="ml-1" type="button">
                                                                    Activer
                                                                </span>
                                                            <?php endif; ?>
                                                            <form id="form_active_<?php echo e($fournisseur->id); ?>" action="<?php echo e(route('fournisseur.destroy',$fournisseur->id)); ?>" method="POST">
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
                    <?php if($fournisseurs->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($fournisseurs->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($fournisseurs->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($fournisseurs->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($fournisseurs->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($fournisseurs->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($fournisseurs->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($fournisseurs->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($fournisseurs->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($fournisseurs->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($fournisseurs->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($fournisseurs->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($fournisseurs->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $fournisseurs->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $fournisseurs->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($fournisseurs->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($fournisseurs->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
		document.querySelector('#FournisseurListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/fournisseur/index.blade.php ENDPATH**/ ?>