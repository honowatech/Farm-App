<?php $__env->startSection('title'); ?>
<?php echo e('Liste des Utilisateurs '); ?>

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
                        <h2 class="float-left mb-0 content-header-title"><?php echo e(__('message.Liste des Utilisateurs')); ?></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('message.Accueil')); ?></a>
                            </li>
                            <li class="breadcrumb-item active"><?php echo e(__('message.Liste des Utilisateurs')); ?>

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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('user.create')); ?>">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form action="<?php echo e(route('user.index')); ?>" class="mt-1 col-lg-3" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="<?php echo e(session()->get('search')); ?>" placeholder="Rechercher un utilisateur">
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
                                        <th><?php echo e(__('message.Noms Et Prénoms')); ?></th>
                                        <th><?php echo e(__('message.E-mail')); ?></th>
                                        
                                        <th><?php echo e(__('message.Rôle')); ?></th>
                                        <th><?php echo e(__('message.Statut')); ?></th>
                                        <th><?php echo e(__('message.Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$users->currentPage() -19 ;
                                        $table = ['secondary','danger','warning','info','light-success','primary','dark'];
                                	?>
                                	<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder text-uppercase">
	                                            	<?php echo e($user->name); ?>

	                                            </span>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	<?php echo e($user->email); ?>

	                                            </span>
	                                        </td>
                                            
                                            <td class="text-uppercase">
                                                <span class="badge badge-pill badge-glow badge-<?php echo e($table[($user->type_user->id + 4) % count($table)]); ?>">
                                                <?php echo e(__('message.'.trim($user->type_user->libelle))); ?>

                                                 </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-glow <?php if($user->statut == 0): ?> badge-danger <?php else: ?> badge-success <?php endif; ?>">
                                            		<?php if($user->statut == 0): ?>
		                                             	<?php echo e(__('message.Désactivé')); ?>

		                                            <?php else: ?>
		                                            	<?php echo e(__('message.Actif')); ?>

		                                            <?php endif; ?>
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" href="<?php echo e(route('user.show',$user->id)); ?>">
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1"><?php echo e(__('message.Détails')); ?></span>
	                                                    </a>
                                                        <a class="dropdown-item" href="<?php echo e(route('user.edit',$user->id)); ?>">
                                                            <i data-feather="edit-2" class="mr-50"></i>
                                                            <span class="ml-1"><?php echo e(__('message.Editer')); ?></span>
                                                        </a>
                                                    <?php if(strtoupper($user->type_user->libelle) != strtoupper('client') && strtoupper($user->type_user->libelle) != strtoupper('clients')): ?>
                                                        <span class="dropdown-item" onclick="actions('form_active_<?php echo e($user->id); ?>', <?php if($user->statut == 1): ?> 'Désactiver' <?php else: ?> 'Activer' <?php endif; ?> ,'<?php echo e(name($user->name)); ?>')">
                                                            <?php if($user->statut == 1): ?>
                                                                <i class="text-danger" data-feather='user-minus'></i>
                                                                <span class="ml-1" type="button">
                                                                   <?php echo e(__('message.Désactiver')); ?>

                                                                </span>
                                                            <?php else: ?>
                                                                <i class="text-success" data-feather='user-check'></i>
                                                                <span class="ml-1" type="button">
                                                                    <?php echo e(__('message.Activer')); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                            <form id="form_active_<?php echo e($user->id); ?>" action="<?php echo e(route('user.destroy',$user->id)); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        </span>
                                                    <?php endif; ?>
                                                    </div>
	                                            </div>
	                                        </td>
	                                    </tr>
	                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($users->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($users->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($users->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($users->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($users->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($users->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($users->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($users->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($users->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($users->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($users->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($users->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($users->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $users->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $users->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($users->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($users->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
		document.querySelector('#UserListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/user/index.blade.php ENDPATH**/ ?>