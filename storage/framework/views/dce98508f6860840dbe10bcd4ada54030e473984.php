<div class="content">
    <div class="container-fluid">
    <?php echo $__env->make('admin.includes.alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.includes.alerts.error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <?php echo $__env->yieldContent('content'); ?>

      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div><?php /**PATH C:\laragon\www\dynamite\resources\views/user/includes/content.blade.php ENDPATH**/ ?>