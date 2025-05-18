
<?php $__env->startSection('css'); ?>
<style>
  /* Basic modal styles without Bootstrap */
  .forgot-password-modal {
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  
  .forgot-password-modal .modal-title {
    color: #000;
    font-size: 28px;
    font-weight: 700;
  }
  
  .forgot-password-modal .form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 12px 15px;
    font-size: 16px;
    display: block;
    width: 100%;
    margin-bottom: 15px;
  }
  
  .forgot-password-modal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: none;
  }
  
  .forgot-password-modal .form-label {
    font-weight: 500;
    color: #333;
    display: block;
    margin-bottom: 8px;
  }
  
  .forgot-password-btn {
    background-color: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 17px;
    transition: all 0.3s ease;
    width: 100%;
    padding: 12px 0;
    cursor: pointer;
  }
  
  .forgot-password-btn:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
  }
  
  /* Custom modal styling without Bootstrap */
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
  }
  
  .modal-container {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1001;
    width: 90%;
    max-width: 500px;
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }
  
  .modal-header {
    position: relative;
    margin-bottom: 20px;
  }
  
  .modal-close {
    position: absolute;
    top: 0;
    right: 0;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    color: #666;
  }
  
  .modal-body {
    margin-bottom: 20px;
  }
  
  .d-none {
    display: none;
  }
  
  .form-group {
    margin-bottom: 16px;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="signin-section">
  <div class="signin-content">
    <!-- ✅ Left side -->
    <div class="signin-left">
      <h2 class="signin-heading"><?php echo e(__('messages.Sign in to')); ?></h2>
      <h3 class="signin-subheading">The Dynamite Box</h3>
      <p class="signin-tagline">
        <?php echo e(__('messages.welcome_tagline')); ?>

      </p>
      <img src="<?php echo e(asset('assets_front/assets/images/dish.png')); ?>" class="signin-dish" alt="Dish"/>
    </div>

    <!-- ✅ Right side -->
    <div class="signin-right">
      <div class="signin-box">
        <p class="signin-welcome"><?php echo e(__('messages.welcome_to')); ?> <span class="red">the dynamite box</span></p>
        <h2 class="signin-title"><?php echo e(__('messages.Sign in')); ?></h2>

        <div class="signin-social">
          <a href="<?php echo e(route('login.google')); ?>" class="social-btn google-btn">
            <i class="fab fa-google"></i> <?php echo e(__('messages.signin_google')); ?>

          </a>
        </div>
        
        <form class="auth-form" action="<?php echo e(route('login')); ?>" method="post">
          <?php echo csrf_field(); ?>
          <label><?php echo e(__('messages.Phone')); ?></label>
          <input 
            type="text" 
            name="phone" 
            placeholder="<?php echo e(__('messages.phone_placeholder')); ?>" 
            value="<?php echo e(old('phone')); ?>"
            class="<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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
          
          <label><?php echo e(__('messages.enter_password')); ?></label>
          <input 
            type="password" 
            name="password" 
            placeholder="<?php echo e(__('messages.password_placeholder')); ?>" 
            class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
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
          
          <div class="signin-options">
             <a href="javascript:void(0);" id="forgotPasswordLink"><?php echo e(__('messages.forgot_password')); ?></a>
            <span><?php echo e(__('messages.no_account')); ?> <a href="<?php echo e(route('register')); ?>"><?php echo e(__('messages.sign_up')); ?></a></span>
          </div>
          
          <button type="submit" class="signin-submit"><?php echo e(__('messages.Sign in')); ?></button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Custom Modal without Bootstrap - Updated to show all fields -->
<div id="modalOverlay" class="modal-overlay"></div>
<div id="forgotPasswordModal" class="modal-container forgot-password-modal">
  <div class="modal-header">
    <h2 class="modal-title"><?php echo e(__('messages.forgot_password')); ?></h2>
    <button type="button" class="modal-close" id="modalClose">&times;</button>
  </div>
  <div class="modal-body">
    <form id="forgotPasswordForm" action="<?php echo e(route('password.update')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      
      <div class="form-group">
        <label for="phone" class="form-label"><?php echo e(__('messages.enter_phone_number')); ?></label>
        <input type="text" 
              class="form-control" 
              id="phone" 
              name="phone" 
              placeholder="<?php echo e(__('messages.phone_placeholder')); ?>" 
              required>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label"><?php echo e(__('messages.new_password')); ?></label>
        <input type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              placeholder="<?php echo e(__('messages.password_placeholder')); ?>" 
              required>
      </div>
      
      <div class="form-group">
        <label for="password_confirmation" class="form-label"><?php echo e(__('messages.confirm_new_password')); ?></label>
        <input type="password" 
              class="form-control" 
              id="password_confirmation" 
              name="password_confirmation" 
              placeholder="<?php echo e(__('messages.password_placeholder')); ?>" 
              required>
      </div>
      
      <button type="submit" class="forgot-password-btn">
        <?php echo e(__('messages.reset_password')); ?>

      </button>
    </form>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get elements
  const forgotPasswordLink = document.getElementById('forgotPasswordLink');
  const modalOverlay = document.getElementById('modalOverlay');
  const modal = document.getElementById('forgotPasswordModal');
  const modalClose = document.getElementById('modalClose');
  const forgotPasswordForm = document.getElementById('forgotPasswordForm');

  // Check if elements exist
  if (!forgotPasswordLink || !modalOverlay || !modal || !modalClose) {
    console.error('Required elements not found');
    return;
  }

  // Open modal
  forgotPasswordLink.addEventListener('click', function(e) {
    e.preventDefault();
    modalOverlay.style.display = 'block';
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
  });

  // Close modal function
  function closeModal() {
    modalOverlay.style.display = 'none';
    modal.style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
    
    // Reset form when closing
    if (forgotPasswordForm) {
      forgotPasswordForm.reset();
    }
  }

  // Close when clicking X
  modalClose.addEventListener('click', closeModal);

  // Close when clicking outside
  modalOverlay.addEventListener('click', function(e) {
    if (e.target === modalOverlay) {
      closeModal();
    }
  });

  // Handle escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modalOverlay.style.display === 'block') {
      closeModal();
    }
  });

  // Form submission client-side validation
  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('<?php echo e(__("messages.passwords_dont_match")); ?>');
        return false;
      }
      
      // If passwords match, form will submit normally to your controller
    });
  }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/login.blade.php ENDPATH**/ ?>