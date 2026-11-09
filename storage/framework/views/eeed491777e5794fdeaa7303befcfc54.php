<?php $__env->startSection('title'); ?>
<?php echo e('Liste des Bandes'); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Liste des Bandes</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des Bandes
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
                    <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('bande.create')); ?>">
                        <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                    </a>
                        <form class="mt-1 col-12" id="search" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="row">

                                <!-- type -->
                                <div class="´mx-auto form-group col-12 col-md-4">
                                    <label><?php echo e(__('message.Type d\'animal')); ?></label>
                                    <select id="bande" class="select2 type form-control" name="id_type_animal" required>
                                        <option selected value=""><?php echo e(__('message.Type d\'animal')); ?></option>
                                        <?php $__currentLoopData = $annimals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $annimal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($annimal->id); ?>"><?php echo e($annimal->nom); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <!-- Date de debut -->
                                <div class="´mx-auto form-group col-12 col-md-4">
                                    <label><?php echo e(__('depenses::translated.Date de début')); ?></label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i data-feather='search'></i></span>
                                        </div>
                                        <input type="text" name="date_debut" class="bg-white form-control date_debut flatpickr-date-time flatpickr-input" placeholder="<?php echo e(__('depenses::translated.Date de début')); ?>" id="date_debut">
                                    </div>
                                </div>

                                <!-- Date de fin -->
                                <div class="´mx-auto form-group col-12 col-md-4">
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
                                        <th>Nom</th>
                                        <th>Date Lancement</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$bandes->currentPage() -19 ;
                                        $table_color = [
                                            'encours' => 'success',
                                            'terminee' => 'danger',
                                        ];
                                	?>
                                	<?php $__currentLoopData = $bandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
	                                            	<?php echo e(ucfirst($bande->nom)); ?>

	                                            </span>
	                                        </td>
                                            <td>
                                                <span class="badge badge-glow badge-light-dark"> Le
                                                    <?php
                                                        $date = new DateTime($bande->date_lancement_bande);
                                                    ?>
                                                    <?php echo e($date->format('d-M-Y')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if( new DateTime($bande->date_lancement_bande) > new DateTime() ): ?>
                                                    <span class="badge badge-glow badge-dark">
                                                        <?php echo e('En attente'); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-glow badge-<?php echo e($table_color[$bande->statut_bande]); ?>">
                                                        <?php echo e($bande->statut_bande == 'encours' ? 'En cours' : ucfirst($bande->statut_bande)); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a class="dropdown-item" href="<?php echo e(route('bande.show',$bande->id)); ?>" >
	                                                        <i data-feather='eye'></i>
	                                                        <span class="ml-1">Informations</span>
	                                                    </a>
                                                        <span class="dropdown-item" onclick="actions('form_delete_<?php echo e($bande->id); ?>', 'Supprimer' ,'<?php echo e($bande->nom); ?>')">
                                                                <i class="text-danger" data-feather='trash'></i>
                                                                <span class="ml-1" type="button">
                                                                    Supprimer
                                                                </span>
                                                            <form id="form_delete_<?php echo e($bande->id); ?>" action="<?php echo e(route('bande.destroy',$bande->id)); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                            </form>
                                                        </span>
                                                        <?php if($bande->statut_bande != 'terminee'): ?>
                                                        <a class="dropdown-item" onclick="response_ajax('info_description1',['<?php echo e($bande->id); ?>'],'<?php echo e(route('get-form-bande',$bande->id)); ?>','GET')" data-toggle="modal" data-target="#description1">
                                                            <i data-feather='minus-circle'></i>
                                                            <span class="ml-1">Stopper la bande</span>
                                                        </a>
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
                    <?php if($bandes->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($bandes->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($bandes->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($bandes->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($bandes->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($bandes->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($bandes->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($bandes->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($bandes->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($bandes->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($bandes->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($bandes->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($bandes->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $bandes->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $bandes->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($bandes->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($bandes->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mx-auto col-10" id="info_description">
                        Infos de chaque Batîment ici !
                    </div>
                </div>
            </div>
            <div  id="disapear" class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="px-2 mx-auto btn btn-success btn-gradient-success">
                    Ok
                </button>
            </div>
            <div id="button_footer" hidden>
                <button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
                <button type="submit" class="mx-auto col-md-5 col-10 5 btn btn-gradient-success btn-success">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="description1" tabindex="-1" aria-labelledby="descriptionTitle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionTitle"> Informations </h5>
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div>
                <div class="modal-body" id="info_description1">
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
		document.querySelector('#BandeListe').classList.add('active');
       $('#no').on('click', function () {
            response_ajax('info_description1', ['<?php echo e($bande->id); ?>'], '<?php echo e(route('gestion_bande', $bande->id)); ?>', 'GET');
        });

        $(document).ready(function() {
            function fetchTransactions() {
                var formData = $('#search').serialize();
                $.ajax({
                    url: "<?php echo e(route('bande.search')); ?>",
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
        });
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/bande/index.blade.php ENDPATH**/ ?>