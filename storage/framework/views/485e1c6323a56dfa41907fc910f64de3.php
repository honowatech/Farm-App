<?php $__env->startSection('title'); ?>
<?php echo e('Détails sur '); ?> <?php echo e(name($agent->nom)); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('menu'); ?>
    <?php
        $role = auth()->user()->type_user->libelle;
    ?>
    <?php echo $__env->make($role.'.menu.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('contenu'); ?>

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
    	       <div class="mb-2 content-header-left col-md-12 col-12">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="float-left mb-0 content-header-title">Infos sur <?php echo e(name($agent->nom)); ?></h2>
                            <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Acceuil</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('agent.index')); ?>">
                                    Liste des agents
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Infos sur <?php echo e(name($agent->nom)); ?>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="app-user-view">
                    <!-- User Card & Plan Starts -->
                    <div class="row">
                        <!-- User Card starts-->
                        <div class="col-md-12">
                            <div class="card user-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-bg-6 col-lg-6 d-flex flex-column justify-content-between border-container-lg">
                                            <div class="user-avatar-section">
                                                <div class="d-flex justify-content-start">
                                                    <img class="rounded img-fluid" src="<?php echo e(asset('app-assets/images/avatars/user.png')); ?>" height="90" width="90" alt="User avatar" />
                                                    <div class="ml-1 d-flex flex-column">
                                                        <div class="mb-1 user-info">
                                                            <h4 class="mb-0 ml-1"><?php echo e(name($agent->nom)); ?></h4>
                                                            <span class="ml-1 card-text" title="<?php echo e($agent->adresse); ?>"><?php echo e(cutText($agent->adresse,15)); ?></span>
                                                        </div>
                                                        <div class="flex-wrap d-flex">
                                                            <a href="<?php echo e(route('agent.edit',$agent->id)); ?>" class="ml-1 btn btn-secondary btn-gradient-secondary btn-sm">Modifier</a>
                                                            <button class="btn ml-1 btn-sm <?php if($agent->statut == 1): ?> btn-gradient-danger btn-danger  <?php else: ?> btn-gradient-success btn-success <?php endif; ?> " onclick="actions('form_active', <?php if($agent->statut == 1): ?> 'Désactiver' <?php else: ?> 'Activer' <?php endif; ?> ,'<?php echo e($agent->nom); ?>')">
                                                                    <?php if($agent->statut == 1): ?>
                                                                        Désactiver
                                                                    <?php else: ?>
                                                                        Activer
                                                                    <?php endif; ?>
                                                                <form id="form_active" action="<?php echo e(route('agent.destroy',$agent->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                </form>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-0 mt-2 col-bg-6 col-lg-6 mt-xl-0">
                                            <div class="user-info-wrapper">
                                                <div class="flex-wrap d-flex d-sm-block d-sm-flex d-none">
                                                    <div class="user-info-title">
                                                        <i data-feather="user" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Noms</span>
                                                    </div>
                                                    <p class="mb-0 card-text"><?php echo e(name($agent->nom)); ?></p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                        <i data-feather="phone" class="mr-1"></i>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone</span>
                                                    </div>
                                                    <p class="mb-0 card-text">
                                                        <span>
                                                        	<?php echo e(phone($agent->telephone)); ?>

                                                        </span></p>
                                                </div>
                                                <div class="flex-wrap d-flex my-50">
                                                    <div class="user-info-title">
                                                         <?php if($agent->statut == 0): ?>
                                                            <i data-feather="x-circle" class="mr-1"></i>
                                                        <?php else: ?>
                                                            <i data-feather="check-circle" class="mr-1"></i>
                                                        <?php endif; ?>
                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Status</span>
                                                    </div>
                                                    <span class="badge badge-pill badge-glow <?php if($agent->statut == 0): ?>badge-danger <?php else: ?> badge-success <?php endif; ?>">
	                                            		<?php if($agent->statut == 0): ?>
			                                             	Désactivé
			                                            <?php else: ?>
			                                            	Actif
			                                            <?php endif; ?>
                                                	</span>

                                                </div>
	                                            <?php if($agent->telephone_secondaire  != null): ?>
	                                                <div class="flex-wrap d-flex my-50">
	                                                    <div class="user-info-title">
	                                                        <i data-feather="phone" class="mr-1"></i>
	                                                        <span class="mb-0 card-text user-info-title font-weight-bold">Téléphone 2</span>
	                                                    </div>
	                                                    <p class="mb-0 card-text">
	                                                        <span>
	                                                        	<?php echo e(phone($agent->telephone_secondaire)); ?>

	                                                        </span></p>
	                                                </div>
	                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                    <!-- zone Timeline & Permissions Ends -->
            </section>
        </div>
    </div>
</div>
    <!-- END: Content-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	<script type="text/javascript">
        function remplir_id_detail(){
            $(document).ready(function() {
              // Récupération des données sélectionnées
              const select_details= $("#select_details").val();

              // Affichage des données sélectionnées dans la console
              valeur = '';
              virgule = ','
              for (var i = select_details.length - 1; i >= 0; i--) {
                    i == 0 ? virgule = '' : virgule = ',';
                 valeur += select_details[i]+virgule;
              }
                document.querySelector('#id_details').value = valeur;
                // console.log(document.querySelector('#id_details').value)
            });
        }
        function  put_details_id(id){
            var matieres  = document.querySelectorAll('.id_details');
            for (i = 0; i < matieres.length; i++) {
                matieres[i].value = id;
            }
        }
        <?php $__errorArgs = ['id_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            document.querySelector('#add_matiere').click()
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            document.querySelector('#AgentListe').classList.add('active');
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/templates/template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/agents/info.blade.php ENDPATH**/ ?>