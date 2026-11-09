<?php $__env->startSection('title'); ?>
<?php echo e('New Dérivée'); ?>

<?php $__env->stopSection(); ?>
<?php if(auth()->user()->type_user->libelle === 'admin'): ?>
    <?php echo $__env->make('admin/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->type_user->libelle === 'agent'): ?>
    <?php echo $__env->make('agent/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php $__env->startSection('contenu'); ?>

<div class="content app-content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
    	    <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Nouvelle dérivée</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?php echo e(route('produit.index')); ?>">Liste des produits</a>
                                </li>
                                <li class="breadcrumb-item"><a href="<?php echo e(route('produit.show',$produit->id)); ?>"><?php echo e(ucfirst($produit->nom)); ?></a>
                                </li>
                                <li class="breadcrumb-item active">Ajouter des dérivées à <?php echo e(ucfirst($produit->nom)); ?>

                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    	<div class="content-body">
            <section class="form-control-repeater">
                <div class="row">
                    <!-- Invoice repeater -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Formulaire d'Ajout</h4>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo e(route('derive_produit.store')); ?>" method="POST" class="invoice-repeater">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="produit" value="<?php echo e($produit->id); ?>">
                                    <div class="col-md-10 col-12 mx-auto">
                                                <div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-12 mx-auto">
                                                                <div class="form-group">
                                                                    <label for="nom">Nom</label>
                                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                                    <div class="form-group">
                                                                        <div class="input-group input-group-merge <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                            <div class="input-group-prepend ">
                                                                                <span class="input-group-text" id="basic-addon1">
                                                                                    <?php echo e($produit->nom); ?>_</span>
                                                                            </div>
                                                                            <input type="text" id="nom" class="form-control border-left pl-1 <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="nom" placeholder="Nom " value="<?php echo e(old('nom')); ?>" / required>
                                                                        </div>
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
                                                        <div class="col-md-6 col-12 mx-auto">
                                                            <div class="form-group">
                                                                <label for="prix_unitaire">Prix unitaire</label>
                                                                <div class="input-group input-group-merge <?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                    <input type="text" class="<?php $__errorArgs = ['prix_unitaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> form-control" placeholder="1,000" id="prix_unitaire" name="prix_unitaire2" onkeyup="format_montant('prix_unitaire')" value="<?php echo e(old('prix_unitaire2')); ?>" required />
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">FCFA</span>
                                                                    </div>
                                                                <?php $__errorArgs = ['prix_unitaire'];
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
                                                            <input type="text" id="prix_unitaire2" name="prix_unitaire" value="<?php echo e(old('prix_unitaire')); ?>" hidden />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-12 mx-auto">
                                                                <div class="form-group text-center">  
                                                                    <label for="min">Minimum</label>
                                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                                    <div class="input-group mx-auto">
                                                                        <input type="number" class="touchspin-color form-control" value="<?php echo e(old('min') == null ? 0 : old('min')); ?>" name="min" id="min" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                                    </div>
                                                                <?php $__errorArgs = ['min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger mx-auto"><?php echo e($message); ?></small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-3 col-12 mx-auto">
                                                                <div class="form-group text-center">  
                                                                    <label for="max">Maximum</label>
                                                                    <span class="text-danger cursor-pointer" title="Obligatoire">*</span>
                                                                    <div class="input-group mx-auto">
                                                                        <input type="number" class="touchspin-color form-control" value="<?php echo e(old('max') == null ? 0 : old('max')); ?>" name="max" id="max" data-bts-button-down-class="btn btn-dark" data-bts-button-up-class="btn btn-dark" required/>
                                                                    </div>
                                                                <?php $__errorArgs = ['max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                    <small class="alert alert-danger mx-auto"><?php echo e($message); ?></small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6 col-12 mx-auto">
                                                                <div class="form-group">
                                                                    <label for="unite"> Choisir une unité</label>
                                                                    <select class="select2 form-control" id="unite" name="unite" required>
                                                                        <option value="">
                                                                            Choisir une unité
                                                                        </option>
                                                                    <?php $__currentLoopData = $unites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($unite->id); ?>" <?php echo e($unite->id == old('unite') ? 'selected' : ''); ?> >
                                                                            <?php echo e($unite->abrege); ?>___<?php echo e($unite->libelle); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                    <?php $__errorArgs = ['unite'];
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
                                                </div>
                                    </div>
                                    <div class="row col-12">
                                        <div class="d-none d-md-block text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                             <button type="reset" class="btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-danger mt-1 mt-md-2 mr-md-4">
                                                <i class="mr-1" data-feather='x'></i> Effacer
                                           </button>
                                            <button type="submit" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-success mt-1 mt-md-2">
                                                <i class="mr-1" data-feather='download'></i> Enregistrer
                                            </button>
                                        </div>
                                        <div class="d-block d-md-none text-center col-bg-5 col-lg-8 col-md-12 col-12 mx-auto">
                                            <button type="submit" class="btn col-8 col-sm-7 col-lg-5  col-xl-3 col-md-4 btn-success mt-1 mt-md-2 mr-md-2">
                                                <i class="mr-1" data-feather='download'></i> Enregistrer
                                            </button>
                                            <button type="reset" class="btn col-8 col-sm-7 col-lg-5 col-xl-3 col-md-4 btn-danger mt-1 mt-md-2">
                                                <i class="mr-1" data-feather='x'></i> Effacer
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice repeater -->
                </div>
            </section> 
    	</div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php if(auth()->user()->type_user->libelle === 'admin'): ?>
    <?php echo $__env->make('admin/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->type_user->libelle === 'agent'): ?>
    <?php echo $__env->make('agent/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php $__env->startSection('javascript'); ?>
	<script type="text/javascript">
		document.querySelector('#ProduitListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>
<?php if(auth()->user()->type_user->libelle === 'admin'): ?>
    <?php echo $__env->make('admin/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(auth()->user()->type_user->libelle === 'agent'): ?>
    <?php echo $__env->make('agent/menu/menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/derive_produit/create.blade.php ENDPATH**/ ?>