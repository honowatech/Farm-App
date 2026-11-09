<?php $__env->startSection('title'); ?>
<?php echo e('New Bande'); ?>

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
                        <h2 class="float-left mb-0 content-header-title">Nouvel Bande</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item active">Ajouter une Bande
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
            </div>
	    <div class="content-body">
<section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Formulaire de création</h3>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="<?php echo e(route('bande.store')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="mx-auto row col-lg-10">
                                                        <!-- Bâtiment -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                           <div class="form-group">
                                                                <label for="batiment">Bâtiment</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="form-group">
                                                                    <select class="select2 form-control" id="batiment" name="batiment" required>
                                                                        <option value="">
                                                                            Choisir un Bâtiment
                                                                        </option>
                                                                        <?php $__currentLoopData = $batiments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batiment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($batiment->id); ?>" <?php echo e($batiment->id == old('batiment') ? 'selected' : ''); ?>>
                                                                                <?php echo e(strtoupper($batiment->nom)); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <?php $__errorArgs = ['batiment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Type d'animal -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="type_animal">Type d'animal</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="form-group">
                                                                    <select class="select2 form-control" id="type_animal" name="type_animal" required>
                                                                        <option value="">Choisir un Type d'animal</option>
                                                                        <?php $__currentLoopData = $types_animal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type_animal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($type_animal->id); ?>" <?php echo e($type_animal->id == old('type_animal') ? 'selected' : ''); ?>>
                                                                                <?php echo e(strtoupper($type_animal->nom)); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <?php $__errorArgs = ['type_animal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nom -->
                                                        <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="nom">Nom</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="input-group input-group-merge <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                    <input type="text" id="nom" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="nom" placeholder="Nom" value="<?php echo e(old('nom')); ?>" required>
                                                                    <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Date de lancement -->
                                                         <div class="mx-auto col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label for="date">Date de lancement</label>
                                                                <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                <div class="input-group input-group-merge <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i class="mr-1" data-feather="calendar"></i></span>
                                                                    </div>
                                                                    <input onchange="seeRecap()" class=" <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> cursor-pointer form-control flatpickr-human-friendly input" placeholder="January 01, 2024" tabindex="0" type="text" id="date" readonly="readonly" name="date" required value="<?php echo e(old('date')); ?>">
                                                                </div>
                                                                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"> <?php echo e($message); ?> </small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                        <!-- Âge de départ et unité -->
                                                        <div class="mx-auto col-md-6 col-12 row">
                                                            <div class="mx-auto col-md-6 col-12 mt-md-1 row">
                                                                <div class="text-center form-group">
                                                                    <label for="age_depart">Age de départ</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <div class="mx-auto input-group">
                                                                        <input type="number" class="touchspin-color form-control" value="<?php echo e(old('age_depart') == null ? 1 : old('age_depart')); ?>" name="age_depart" id="age_depart" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                                        <?php $__errorArgs = ['age_depart'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                        <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mx-auto col-sm-6 col-12">
                                                                <div class="text-center form-group">
                                                                    <label for="unite_age_depart">Unité de mesure</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <select class="form-control" id="unite_age_depart" name="unite_age_depart" required>
                                                                        <option value="jour" <?php echo e(old('unite_age_depart') == 'jour' ? 'selected' : ''); ?>>Jour</option>
                                                                        <option value="semaine" <?php echo e(old('unite_age_depart') == 'semaine' ? 'selected' : ''); ?>>Semaine</option>
                                                                        <option value="mois" <?php echo e(old('unite_age_depart') == 'mois' ? 'selected' : ''); ?>>Mois</option>
                                                                        <option value="annee" <?php echo e(old('unite_age_depart') == 'annee' ? 'selected' : ''); ?>>Année</option>
                                                                    </select>
                                                                    <?php $__errorArgs = ['unite_age_depart'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Nombre de sujets et prix d'acquisition -->
                                                        <div class="mx-auto col-md-6 col-12 row">
                                                            <div class="mx-auto col-md-6 col-12 mt-md-1 row">
                                                                <div class="text-center form-group">
                                                                    <label for="nbre_sujet">Nombre de sujets</label>
                                                                    <span class="cursor-pointer text-danger" title="Obligatoire">*</span>
                                                                    <div class="mx-auto input-group">
                                                                        <input type="number" class="touchspin-color form-control" id="nbre_sujet" value="<?php echo e(old('nbre_sujet') == null ? 1 : old('nbre_sujet')); ?>" data-bts-button-down-class="btn btn-dark"  name="nbre_sujet"data-bts-button-up-class="btn btn-dark" required/>
                                                                        <?php $__errorArgs = ['nbre_sujet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                        <small class="alert alert-danger"><?php echo e($message); ?></small>
                                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mx-auto col-sm-6 col-12">
                                                                <div class="form-group">
                                                                    <label for="prix_acquisition">Prix d'acquisition</label>
                                                                    <div class="input-group input-group-merge <?php $__errorArgs = ['prix_acquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                        <input type="text" class="<?php $__errorArgs = ['prix_acquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> form-control" placeholder="1,000" id="prix_acquisition" name="prix_acquisition2" onkeyup="format_montant('prix_acquisition')" value="<?php echo e(old('prix_acquisition2')); ?>" required />
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">FCFA</span>
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" id="prix_acquisition2" name="prix_acquisition" value="<?php echo e(old('prix_acquisition')); ?>" hidden />
                                                                </div>
                                                                <?php $__errorArgs = ['prix_acquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger"> <?php echo e($message); ?> </small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <!-- Boutons -->
                                            <div class="row col-12">
                                                <div class="mx-auto text-center d-none d-md-block col-lg-8">
                                                    <button type="reset" class="btn btn-danger col-4">Effacer</button>
                                                    <button type="submit" class="btn btn-success col-4">Enregistrer</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
	    </div>
	</div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
	<script type="text/javascript">
		document.querySelector('#BandeAjouter').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/bande/create.blade.php ENDPATH**/ ?>