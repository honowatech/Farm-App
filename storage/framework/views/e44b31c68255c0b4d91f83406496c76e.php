<?php $__env->startSection('title'); ?>
<?php echo e('Liste des Ramassages'); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Liste des Ramassages</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Ramassages
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('ramassage.create')); ?>">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form class="mt-1 col-12" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="mx-auto row">
                                <!-- type -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label><?php echo e(__('message.Bande')); ?></label>
                                    <select id="bande" class="select2 type form-control" name="id_bande" required>
                                        <option selected value=""><?php echo e(__('message.Choix de la bande')); ?></option>
                                        <?php $__currentLoopData = $bandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($bande->id); ?>"><?php echo e($bande->nom); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <!-- Date de debut -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label><?php echo e(__('depenses::translated.Date de début')); ?></label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_debut" class="bg-white form-control date_debut flatpickr-date-time flatpickr-input" placeholder="<?php echo e(__('depenses::translated.Date de début')); ?>" id="date_debut">
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="mx-auto form-group col-12 col-md-3">
                                    <label><?php echo e(__('depenses::translated.Date de fin')); ?></label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_fin" class="bg-white form-control date_fin flatpickr-date-time flatpickr-input" placeholder="<?php echo e(__('depenses::translated.Date de fin')); ?>" id="date_fin">
                                    </div>
                                </div>
                                

                            </div>
                            <input type="hidden" name="page" id="page" value="">
                        </form>
                    </div>
                </div>
                <!-- Table without card start -->
                <div class="row" id="table-without-card">
                        <div class="table-responsive" id="results">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Bande</th>
                                        <th>Produit</th>
                                        <th>Type d'animal</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$ramassages->currentPage() -19 ;
                                	?>
                                	<?php $__currentLoopData = $ramassages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ramassage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    <?php echo e(ucfirst($ramassage->bande->nom)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    <?php echo e(ucfirst($ramassage->produit->nom)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder">
                                                    <?php echo e(ucfirst($ramassage->produit->type_animal->nom)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-glow badge-light-dark">
                                                    <?php
                                                        $date = new DateTime($ramassage->to_do_date);
                                                    ?>
                                                    <?php echo e($date->format('d-M-Y')); ?>

                                                </span> à
                                                <span class="badge badge-glow badge-light-dark">
                                                    <?php echo e($date->format('H')); ?>h:<?php echo e($date->format('i')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-glow <?php if($ramassage->statut == 0): ?> badge-danger <?php else: ?> badge-success <?php endif; ?>">
                                                    <?php if($ramassage->statut == 0): ?>
                                                        Supprimé
                                                    <?php else: ?>
                                                        Effectué
                                                    <?php endif; ?>
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#infos<?php echo e($ramassage->id); ?>">
                                                            <i data-feather='shopping-bag'></i>
                                                            <span class="ml-1">Détails</span>
                                                        </a>
                                                        
                                                        <?php if($ramassage->statut == 1): ?>
                                                            <span class="dropdown-item" onclick="actions('form_delete_<?php echo e($ramassage->id); ?>', 'Supprimer' )">
                                                                    <i class="text-danger" data-feather='trash'></i>
                                                                    <span class="ml-1" type="button">
                                                                        Supprimer
                                                                    </span>
                                                                <form id="form_delete_<?php echo e($ramassage->id); ?>" action="<?php echo e(route('ramassage.destroy',$ramassage->id)); ?>" method="POST">
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
                    <?php if($ramassages->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($ramassages->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($ramassages->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($ramassages->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($ramassages->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($ramassages->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($ramassages->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($ramassages->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($ramassages->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($ramassages->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($ramassages->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($ramassages->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($ramassages->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $ramassages->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $ramassages->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($ramassages->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($ramassages->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
<?php $__currentLoopData = $ramassages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ramassage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="infos<?php echo e($ramassage->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">infos sur le ramassage de <?php echo e(ucfirst($ramassage->produit->nom)); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                       <b>nom du produit: </b> <span class="badge badge-glow badge-light-dark"><?php echo e(ucfirst($ramassage->produit->nom)); ?></span><br>
                       <b>date et heure du rammassage: </b><span class="badge badge-glow badge-light-dark">
                                                    <?php
                                                        $date = new DateTime($ramassage->to_do_date);
                                                    ?>
                                                    <?php echo e($date->format('d-M-Y')); ?>

                                                </span> à
                                                <span class="badge badge-glow badge-light-dark">
                                                    <?php echo e($date->format('H')); ?>h:<?php echo e($date->format('i')); ?>

                                                </span> <br>
                       <b>nombre de produit ramassé: </b><span class="badge badge-glow badge-light-dark"> <?php echo e(json_decode($ramassage->valeurs_derive, true)[0]); ?></span><br>
                       
                       <b>Provenance: </b><span class="badge badge-glow badge-light-dark"><?php echo e($ramassage->bande->nom); ?> </span>
                    </p>
                </div>
                <div class="text-center modal-footer">
                    <button type="button" class="text-center btn btn-success" data-dismiss="modal">ok</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
	<script type="text/javascript">
        function mettre(number_page){
           document.querySelector('#page').value = number_page;
           document.querySelector('#search').submit()
        }
		document.querySelector('#RamassageListe').classList.add('active');
        function fetchTransactions() {
            var formData = $('#search').serialize();
            $.ajax({
                url: "<?php echo e(route('ramassage.search')); ?>",
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('#results').html(response); // Insérer le HTML généré par Blade
                    feather.replace(); // Recharger les icônes de Feather
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    // Gérer les erreurs éventuelles
                }
            });
        }

    // Écouter les événements de changement sur les inputs
    $('#search input, #search select').on('change', function() {
        fetchTransactions(); // Appel de la fonction d'actualisation des résultats
    });
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/ramassage/index.blade.php ENDPATH**/ ?>