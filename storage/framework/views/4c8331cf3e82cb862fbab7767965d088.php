<div class="col-md-6 col-lg-4 col-12">
	<div class="card">
		<div class="card-body">
        	<h4 class="text-center card-title"><?php echo e($setting['title']); ?></h4>
        	<div class="row">
        		<div class="mx-auto text-center col-md-6 col-10">
           			<a class="col-md-10 col-12 btn btn-info waves-effect" href="<?php echo e(route($setting['ressource'].'.index')); ?>">
           				<i data-feather='eye' class="mr-25"></i>
           				<span>Voir</span>
           			</a>
        		</div>
        		<div class="mx-auto text-center col-md-6 col-10">
           			<a class="col-md-12 col-12 btn btn-dark waves-effect" href="<?php echo e(route($setting['ressource'].'.create')); ?>">
           				<i data-feather="plus" class="mr-25"></i>
           				<span>Ajouter</span>
           			</a>
        		</div>
           	</div>
        </div>
	</div>
</div><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/setting/foods-setting/include.blade.php ENDPATH**/ ?>