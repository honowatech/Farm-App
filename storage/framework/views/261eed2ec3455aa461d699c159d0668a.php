
<?php if($nutritions->isNotEmpty()): ?>

    <?php
        // Étape 1: Obtenir le nom de l'animal une seule fois.
        // Puisque tous les enregistrements concernent le même animal, on prend le premier.
        $animalName = $nutritions->first()->planning->nom;

        // Étape 2: Grouper tous les enregistrements par le nom de l'aliment.
        // Cela crée une structure parfaite pour l'affichage :
        // 'Aliment A' => [détails pour 0-10j, détails pour 11-20j],
        // 'Aliment B' => [détails pour 0-5j, ...]
        $groupedNutritions = $nutritions->groupBy('alimentName.nom_produit');
    ?>

    <!-- Titre principal du Planning -->
    <div class="mb-2 text-center">
        <h4>Planning Alimentaire pour les <span class="badge badge-success"><?php echo e($animalName); ?></span></h4>
        <hr>
    </div>

    <!-- Boucle sur chaque groupe d'aliment -->
    <?php $__currentLoopData = $groupedNutritions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alimentName => $nutritionDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-2 card">
            <div class="card-header bg-light">
                <h5 class="mb-0 card-title">Aliment : <span class="font-weight-bold text-primary"><?php echo e($alimentName); ?></span></h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Tranche d'âge (jours)</th>
                            <th>Quantité par animal</th>
                            <th>Fréquence</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php $__currentLoopData = $nutritionDetails->sortBy('age_min'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nutrition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center">
                                    De <strong><?php echo e($nutrition->age_min); ?></strong> à <strong><?php echo e($nutrition->age_max); ?></strong>
                                </td>
                                <td>
                                    
                                    <strong><?php echo e($nutrition->quantite_aliment); ?></strong> <?php echo e($nutrition->unite->abrege ?? $nutrition->unite->libelle); ?>

                                </td>
                                <td>
                                    
                                    <?php
                                        $periode_texte = '';
                                        switch ($nutrition->period) {
                                            case 'day':
                                                $periode_texte = 'jour';
                                                break;
                                            case 'week':
                                                $periode_texte = 'semaine';
                                                break;
                                            case 'month':
                                                $periode_texte = 'mois';
                                                break;
                                            default:
                                                $periode_texte = $nutrition->period; // Fallback
                                        }
                                    ?>
                                    <strong><?php echo e($nutrition->frequency); ?></strong> fois par <?php echo e($periode_texte); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
    
    <div class="p-2 text-center rounded border bg-light">
        <h5 class="text-danger">Aucun planning d'alimentation défini</h5>
        <p class="mb-0 text-muted">Aucun détail n'est disponible pour ce type d'animal.</p>
    </div>
<?php endif; ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/setting/foods-setting/show.blade.php ENDPATH**/ ?>