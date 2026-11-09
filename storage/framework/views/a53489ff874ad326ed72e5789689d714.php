<div class="col-md-6 col-lg-4 col-12">
	<div class="card">
		<div class="card-body">
        	<h4 class="text-center card-title"><?php echo e($setting['title']); ?></h4>
        	<div class="row">
        		<div class="mx-auto text-center col-md-6 col-10">
           			<button class="col-md-10 col-12 btn btn-info waves-effect" id="get_unite" onclick="classer('modifiated','modal-lg');response_ajax('info_gestion','','<?php echo e(route($setting['ressource'].'.index')); ?>','GET')" data-toggle="modal" data-target="#setting">
           				<i data-feather='clipboard' class="mr-25"></i>
           				<span>Liste</span>
           			</button>
        		</div>
        		<div class="mx-auto text-center col-md-6 col-10">
           			<button class="col-md-12 col-12 btn btn-dark waves-effect" onclick="classer('modifiated','modal-sm');response_ajax('info_gestion',['admin.pages.setting.unites.create'],'<?php echo e(route($setting['ressource'].'.create')); ?>','GET')" data-toggle="modal" data-target="#setting">
           				<i data-feather='plus' class="mr-25"></i>
           				<span>Ajouter</span>
           			</button>
        		</div>
           	</div>
        </div>
	</div>
</div><?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/setting/unites/include.blade.php ENDPATH**/ ?>