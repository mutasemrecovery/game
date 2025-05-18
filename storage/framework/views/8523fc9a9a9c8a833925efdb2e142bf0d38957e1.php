

<?php $__env->startSection('content'); ?>
<section class="privacy-policy-section">
    <div class="container">
        <div class="privacy-policy-header">
            <h1 class="privacy-policy-title"><?php echo e(__('messages.Terms of Use')); ?></h1>
        </div>

        <div class="privacy-policy-content">
            <div class="privacy-policy-section">
                <h2><?php echo e($locale === 'en' ? $page->title_en : $page->title_ar); ?></h2>
                <p><?php echo $locale === 'en' ? $page->content_en : $page->content_ar; ?></p>
            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/terms.blade.php ENDPATH**/ ?>