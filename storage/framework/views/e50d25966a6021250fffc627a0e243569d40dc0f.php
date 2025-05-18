

<?php $__env->startSection('content'); ?>
<section class="signin-section">
  <div class="signin-content">
    <!-- ✅ Left side -->
    <div class="signin-left">
      <h2 class="signin-heading"><?php echo e(__('messages.Sign Up to')); ?></h2>
      <h3 class="signin-subheading">The Dynamite Box</h3>
      <p class="signin-tagline">
        <?php echo e(__('messages.welcome_tagline')); ?>

      </p>
      <img src="<?php echo e(asset('assets_front/assets/images/register.png')); ?>" class="signin-dish" alt="Dish"/>
    </div>

    <!-- ✅ Right side -->
    <div class="signin-right">
      <div class="signin-box">
        <p class="signin-welcome"><?php echo e(__('messages.welcome_to')); ?> <span class="red">the dynamite box</span></p>
        <h2 class="signin-title"><?php echo e(__('messages.Sign Up')); ?></h2>

        <form class="auth-form" action="<?php echo e(route('register')); ?>" method="post">
          <?php echo csrf_field(); ?>
          <div class="auth-form-group">
            <label for="name"><?php echo e(__('messages.username')); ?></label>
            <input 
              type="text" 
              id="name" 
              name="name" 
              class="auth-input-full <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
              placeholder="<?php echo e(__('messages.username_placeholder')); ?>" 
              value="<?php echo e(old('name')); ?>" 
              required 
            />
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback" role="alert">
                <strong><?php echo e($message); ?></strong>
              </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          
          <div class="auth-form-group">
            <label for="phone"><?php echo e(__('messages.contact_number')); ?></label>
            <input 
              type="tel" 
              id="phone" 
              name="phone" 
              class="auth-input-full <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
              placeholder="<?php echo e(__('messages.phone_placeholder')); ?>" 
              value="<?php echo e(old('phone')); ?>" 
              required 
            />
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback" role="alert">
                <strong><?php echo e($message); ?></strong>
              </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          
          <div class="auth-form-group">
            <label for="password"><?php echo e(__('messages.enter_password')); ?></label>
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="auth-input-full <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
              placeholder="<?php echo e(__('messages.password_placeholder')); ?>" 
              required 
            />
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="invalid-feedback" role="alert">
                <strong><?php echo e($message); ?></strong>
              </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          
          <div class="auth-options">
            <a href="<?php echo e(route('password.request')); ?>"><?php echo e(__('messages.forgot_password')); ?></a>
            <span><?php echo e(__('messages.have_account')); ?> <a href="<?php echo e(route('login')); ?>"><?php echo e(__('messages.sign_in')); ?></a></span>
          </div>
          
          <button type="submit" class="auth-submit-btn"><?php echo e(__('messages.Sign Up')); ?></button>
        </form>
        
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/register.blade.php ENDPATH**/ ?>