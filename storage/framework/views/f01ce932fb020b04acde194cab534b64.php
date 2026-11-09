<?php $__env->startSection('title'); ?>
<?php echo e('Tous les ventes'); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Liste des ventes</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Liste des ventes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            </div>
    <div class="pr-1 pl-1 card">

            <div class="content-body">
                <div class="row bg-light-secondary">
                    <div class="col-12">
                        <a class="m-1 btn btn-secondary btn-gradient-secondary" href="<?php echo e(route('vente.create')); ?>">
                            <i data-feather='plus' style="width: 20px;height: 20px;"></i>
                        </a>
                    </div>

                    <form  class="mt-1 col-12" id="search" method="GET">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <!-- Date de debut -->
                            <div class="form-group col-12 col-md-2">
                                <label>Date de début</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="text" name="date_debut" class="bg-white form-control date_debut flatpickr-basic flatpickr-input" placeholder="Date début" id="date_debut">
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="form-group col-12 col-md-2">
                                <label>Date de fin</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="text" name="date_fin" class="bg-white form-control date_fin flatpickr-basic flatpickr-input" placeholder="Date fin" id="date_fin">
                                </div>
                            </div>

                            <!-- Produit -->
                            <div class="form-group col-12 col-md-2">
                                <label>Produit</label>
                                <select id="produit" class="select2 produit form-control <?php $__errorArgs = ['num_produit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="id_produit" required>
                                    <option selected value="">Choix produit</option>
                                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($produit->id); ?>"> <?php echo e($produit->nom); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Client -->
                            <div class="form-group col-12 col-md-2">
                                <label>Client</label>
                                <select id="client" class="select2 client form-control <?php $__errorArgs = ['num_agent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="id_client" required>
                                    <option selected value="">Choix client</option>
                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option class="client" value="<?php echo e($client->id); ?>"><?php echo e($client->nom); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <!-- Recherche -->
                            <div class="form-group col-12 col-md-2">
                                <label>Recherche</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i data-feather='search'></i></span>
                                    </div>
                                    <input type="search" class="form-control" name="recherche" value="<?php echo e(session()->get('search')); ?>" placeholder="Rechercher une vente">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="page" id="page" value="">
                    </form>
                </div>
            </div>
                <!-- Table without card start -->
                <div class="row" id="table-without-card">
                        <div class="table-responsive" id = "results">
                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th>N°</th>
                                        <th>Nom client</th>
                                        <th>Nom Fournisseur</th>
                                        <th>Date </th>
                                        <th>Total </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
                                    	$count = 20*$ventes->currentPage() -19 ;
                                        $img = [
                                            'mobilemoney' => 'app-assets/images/logo/mtn.svg',
                                            'orangemoney' => 'app-assets/images/logo/orange.png',
                                            'espece' => 'app-assets/images/logo/espece.webp',
                                            'bank' => 'app-assets/images/logo/bank.png',
                                        ];
                                        $title = [
                                            'mobilemoney' => 'Mtn MOMO',
                                            'orangemoney' => 'Orange Money',
                                            'espece' => 'Espèce',
                                            'bank' => 'Paiement Bancaire'
                                        ];
                                	?>
                                	<?php $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                                    <tr class="text-center">
	                                        <td>
	                                        	<?php echo e($count); ?>

	                                        	<?php $count++ ?>
	                                        </td>
	                                        <td>
	                                            <span class="font-weight-bolder">
                                                    <?php echo e(ucfirst($vente->facture->client_facture->nom)); ?>

	                                            </span>
	                                        </td>
                                            <td>
                                                <span class="text-center text-dark font-weight-bolder">
                                                    <?php echo e(name($vente->saver->name)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-glow badge-secondary bg-secondary bg-lighten-3"> Le
                                                    <?php echo e(Ladate($vente->date_vente)); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bolder text-truncate">
                                                    <?php echo e(ucfirst($vente->facture->total_ttc)); ?> FCFA
                                                </span>
                                            </td>
	                                        <td>
	                                            <div>
	                                                <button type="button" style="margin-bottom: 0px !important;" class="btn btn-sm dropdown-toggle hide-arrow" data-toggle="dropdown">
	                                                    <i data-feather="more-vertical"></i>
	                                                </button>
	                                                <div class="dropdown-menu">
	                                                    <a href="<?php echo e(route('facturation',$vente->id)); ?>">
	                                                        <i data-feather='archive'></i>
	                                                        <span class="ml-1">Detail transaction</span>
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
                    <?php if($ventes->hasPages()): ?>
                        <nav class="justify-items-center mt-1 d-flex col-12 justify-content-center">
                            <div class="mx-auto row justify-content-center flex-fill d-md-none">
                                <div class="mr-1 d-md-none">
                                    <p class="small text-muted">
                                        <?php echo __('De'); ?>

                                        <span class="fw-semibold"><?php echo e($ventes->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($ventes->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($ventes->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>
                                <ul class="pagination">
                                    
                                    <?php if($ventes->onFirstPage()): ?>
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link">Précédent</span>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($ventes->currentPage()-1); ?>)" rel="prev">Suivant</a>
                                        </li>
                                    <?php endif; ?>

                                    
                                    <?php if($ventes->hasMorePages()): ?>
                                        <li class="page-item">
                                            <a class="page-link" onclick="mettre(<?php echo e($ventes->currentPage()+1); ?>)" rel="next"><?php echo app('translator')->get('pagination.next'); ?></a>
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

                                        <span class="fw-semibold"><?php echo e($ventes->firstItem()); ?></span>
                                        <?php echo __('à'); ?>

                                        <span class="fw-semibold"><?php echo e($ventes->lastItem()); ?></span>
                                        <?php echo __('sur'); ?>

                                        <span class="fw-semibold"><?php echo e($ventes->total()); ?></span>
                                        <?php echo __('resultats'); ?>

                                    </p>
                                </div>

                                <div>
                                    <ul class="pagination">
                                        
                                        <?php if($ventes->onFirstPage()): ?>
                                            <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($ventes->currentPage()-1); ?>)" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $ventes->links()->elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <?php if(is_string($element)): ?>
                                                <li class="page-item disabled" aria-disabled="true"><span class="page-link"><?php echo e($element); ?></span></li>
                                            <?php endif; ?>

                                            
                                            <?php if(is_array($element)): ?>
                                                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($page == $ventes->currentPage()): ?>
                                                        <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($page); ?></span></li>
                                                    <?php else: ?>
                                                        <li class="page-item"><a class="page-link" onclick="mettre(<?php echo e($page); ?>)"><?php echo e($page); ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($ventes->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" onclick="mettre(<?php echo e($ventes->currentPage()-1); ?>)" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
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
                <button type="button" class="m-0 close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mx-auto col-10" id="info_description">
                        Une ligne D'ajout de stock s'affichera ici
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center justify-content-md-apt-1">
                <button data-dismiss="modal" class="px-2 mx-auto btn btn-success btn-gradient-success">
                    <i data-feather='check' style="width: 25px;height: 25px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<div id="button_footer" hidden>
    <button type="button" class="mx-auto col-md-5 col-10 btn btn-gradient-danger btn-danger" data-dismiss="modal">Annuler</button>
    <form method="POST" class="p-0 m-0 mx-auto destroy_form col-md-5 col-10">
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

    $(document).ready(function() {
    function fetchTransactions() {
        var formData = $('#search').serialize();
        $.ajax({
            url: "<?php echo e(route('vente.search')); ?>",
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

    // Optionnel : déclencher une recherche au chargement de la page
    fetchTransactions();
});

    document.getElementById('venteListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/vente/index.blade.php ENDPATH**/ ?>