<?php $__env->startSection('title'); ?>
<?php echo e('Liste des séances de vaccination '); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Liste des séances de vaccination</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des séances de vaccination
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('seanceVaccination.create')); ?>"><i data-feather='plus' style="width: 20px;height: 20px;"></i></a>
                        <form action="<?php echo e(route('seanceVaccination.index')); ?>" class="mt-1 col-lg-3" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="<?php echo e(session()->get('search')); ?>" placeholder="Rechercher un seance Vaccination" autofocus>
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
                                        <th>Veterinaire</th>
                                        <th>Bande</th>
                                        <th>Vaccin</th>
                                        <th>Date</th>
                                        <th>Verification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$seance_Vaccinations->currentPage() -19 ;
                                        $table = ['secondary','danger','warning','info','primary','dark'];
                                	?>
                                	<?php $__currentLoopData = $seance_Vaccinations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seance_Vaccination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
                                            <td>
                                                <span class="font-weight-bolder">

                                                    <?php echo e(name($seance_Vaccination->veterinaire->nom)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    <?php echo e(name($seance_Vaccination->bande->nom)); ?>

                                                </span>
                                            </td>
	                                        <td>
                                                <span class="font-weight-bolder">
                                                        <?php echo e($seance_Vaccination->vaccin_attribuer); ?>

                                                </span>
	                                        </td>
                                            <td>
                                                <span class="badge badge-glow badge-secondary">
                                                    Le <?php echo e(Ladate($seance_Vaccination->created_at)); ?> à <?php echo e(Heure($seance_Vaccination->created_at)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($seance_Vaccination->verification == 1): ?>
                                                    <div class="badge badge-glow badge-success">
                                                        Oui
                                                <?php else: ?>
                                                    <div class="badge badge-glow badge-danger">
                                                        Non
                                                <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
	                                            <div>
                                                    <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" onclick="response_ajax('info_description',['<?php echo e($seance_Vaccination->id); ?>'],'<?php echo e(route('seanceVaccination.show',$seance_Vaccination->id)); ?>','GET')" data-toggle="modal" data-target="#description">
                                                            <i data-feather='eye'></i>
                                                            <span class="ml-1">Informations</span>
                                                        </a>
                                                        <a class="dropdown-item" href="<?php echo e(route('seanceVaccination.edit',$seance_Vaccination->id)); ?>">
                                                            <i data-feather="edit-2" class=""></i>
                                                            <span class="ml-1">Editer</span>
                                                        </a>
                                                        <span class="dropdown-item" onclick="actions('form_delete_<?php echo e($seance_Vaccination->id); ?>', 'Supprimer' ,'La séance')">
                                                                <i class="text-danger" data-feather='trash'></i>
                                                                <span class="ml-1" type="button">
                                                                    Supprimer
                                                                </span>
                                                            <form id="form_delete_<?php echo e($seance_Vaccination->id); ?>" action="<?php echo e(route('seanceVaccination.destroy',$seance_Vaccination->id)); ?>" method="POST">
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
                    <?php if($seance_Vaccinations->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($seance_Vaccinations->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($seance_Vaccinations->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($seance_Vaccinations->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($seance_Vaccinations->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($seance_Vaccinations->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($seance_Vaccinations->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($seance_Vaccinations->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $seance_Vaccinations->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $seance_Vaccinations->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($seance_Vaccinations->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($seance_Vaccinations->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-0 row">
                    <div class="mx-auto col-10" id="info_description">
                        Infos sur la vaccination!
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="px-2 mx-auto btn btn-success btn-gradient-success">
                    Ok
                </button>
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
		document.querySelector('#SeanceVaccinationListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/seanceVaccination/index.blade.php ENDPATH**/ ?>