<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Dynamite Box</title>
  <link rel="stylesheet" href="<?php echo e(asset('assets_front/css/style.css')); ?>" />
  <link rel="stylesheet" href="<?php echo e(asset('assets_front/css/login.css')); ?>"/>
  <link rel="stylesheet" href="<?php echo e(asset('assets_front/css/menu.css')); ?>" />
  <script src="<?php echo e(asset('assets_front/js/main.js')); ?>"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <?php echo $__env->yieldContent('css'); ?>
  <?php echo $__env->yieldContent('script'); ?>
</head>
<body>
    
  <!-- Navbar -->
  <?php echo $__env->make('user.includes.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->make('user.includes.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- Footer -->
  <?php echo $__env->make('user.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\dynamite\resources\views/layouts/user.blade.php ENDPATH**/ ?>