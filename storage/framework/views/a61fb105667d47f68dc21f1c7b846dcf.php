<div class="mb-1"> 
    <h5 class="text-uppercase font-weight-bolder text-center">Nom : </h5>
    <div class="divider my-2">
        <div class="divider-text text-uppercase">
            <?php echo e($batiment->nom); ?>

        </div>
    </div>
    <h5 class="text-uppercase font-weight-bolder text-center">Description : </h5>  
            <?php if( textBreack($batiment->description, 60) === true ): ?>
          <p class=" text-center"><?php echo e($batiment->description); ?></p>  
            <?php else: ?> 
           <p><?php echo e(textBreack($batiment->description, 60)); ?></p>
            <?php endif; ?>
</div>

<?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/batiment/info.blade.php ENDPATH**/ ?>