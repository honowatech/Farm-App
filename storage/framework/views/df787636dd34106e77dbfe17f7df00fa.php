<div class="mb-1">
    <h4 class="text-uppercase font-weight-bolder text-center"> Description : </h4>  
            <?php if( textBreack($seanceVaccination->description, 60) === true ): ?>
          <p class=" text-center"><?php echo e($seanceVaccination->description); ?></p>  
            <?php else: ?> 
           <p><?php echo e(textBreack($seanceVaccination->description, 60)); ?></p>
            <?php endif; ?>
</div>
<?php /**PATH /home/uyt145/public_html/subdomaines/farmapp/resources/views/admin/pages/seanceVaccination/info.blade.php ENDPATH**/ ?>