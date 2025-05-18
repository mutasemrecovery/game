

<?php $__env->startSection('content'); ?>
<section class="profile-section">
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-user-info">
                <div class="profile-user-text">
                    <h3 class="profile-username"><?php echo e($user->name); ?></h3>
                    <p class="profile-email"><?php echo e($user->email); ?></p>
                </div>
            </div>
        </div>
        
        <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <form action="<?php echo e(route('profile.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="profile-info-grid">
                <div class="profile-info-block">
                    <label for="name" class="profile-info-label"><?php echo e(__('messages.full_name')); ?></label>
                    <input type="text" id="name" name="name" class="profile-info-box" value="<?php echo e($user->name); ?>" required>
                </div>
                
                <div class="profile-info-block">
                    <label for="phone" class="profile-info-label"><?php echo e(__('messages.phone_number')); ?></label>
                    <input type="text" id="phone" name="phone" class="profile-info-box" value="<?php echo e($user->phone); ?>">
                </div>
                
                <div class="profile-info-block">
                    <label for="current_password" class="profile-info-label"><?php echo e(__('messages.current_password')); ?></label>
                    <input type="password" id="current_password" name="current_password" class="profile-info-box">
                    <p class="profile-info-hint"><?php echo e(__('messages.leave_blank_if_no_password_change')); ?></p>
                </div>
                
                <div class="profile-info-block">
                    <label for="new_password" class="profile-info-label"><?php echo e(__('messages.new_password')); ?></label>
                    <input type="password" id="new_password" name="new_password" class="profile-info-box">
                </div>
                
                <div class="profile-info-block">
                    <label for="new_password_confirmation" class="profile-info-label"><?php echo e(__('messages.confirm_password')); ?></label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="profile-info-box">
                </div>
            </div>
            
            <div class="profile-actions">
                <button type="submit" class="save-profile-btn"><?php echo e(__('messages.save')); ?></button>
                <a href="<?php echo e(route('profile')); ?>" class="cancel-profile-btn"><?php echo e(__('messages.cancel')); ?></a>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/edit-profile.blade.php ENDPATH**/ ?>