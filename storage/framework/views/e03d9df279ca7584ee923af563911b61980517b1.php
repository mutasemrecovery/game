

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
            <a href="<?php echo e(route('profile.edit')); ?>" class="edit-profile-btn"><?php echo e(__('messages.edit')); ?></a>
        </div>
        
        <div class="profile-info-grid">
            <div class="profile-info-block">
                <label class="profile-info-label"><?php echo e(__('messages.full_name')); ?></label>
                <div class="profile-info-box"><?php echo e($user->name); ?></div>
            </div>
            <div class="profile-info-block">
                <label class="profile-info-label"><?php echo e(__('messages.phone_number')); ?></label>
                <div class="profile-info-box"><?php echo e($user->phone ?? __('messages.pending')); ?></div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/profile.blade.php ENDPATH**/ ?>