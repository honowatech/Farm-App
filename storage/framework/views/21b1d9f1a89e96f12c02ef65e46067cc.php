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
</table><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/vente/result_ajax.blade.php ENDPATH**/ ?>