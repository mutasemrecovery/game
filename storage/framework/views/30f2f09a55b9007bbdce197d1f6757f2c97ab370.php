

<?php $__env->startSection('content'); ?>
<section class="menu-section">
  <h2 class="menu-title"><?php echo e(__('messages.Menu')); ?></h2>
  
  <!-- Categories Tabs -->
  <ul class="menu-tabs">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li class="menu-tab <?php echo e($category->id == $firstCategoryId ? 'active' : ''); ?>" data-category="<?php echo e($category->id); ?>">
        <?php echo e($locale == 'en' ? $category->name_en : $category->name_ar); ?>

      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
  
  <!-- Products -->
  <div class="menu-products">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="menu-category" id="category-<?php echo e($category->id); ?>" style="<?php echo e($category->id == $firstCategoryId ? '' : 'display: none;'); ?>">
        <h3 class="category-title"><?php echo e($locale == 'en' ? $category->name_en : $category->name_ar); ?></h3>
        <div class="menu-grid">
          <?php $__empty_1 = true; $__currentLoopData = $category->products->where('status', 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <a href="<?php echo e(route('product.details',$product->id)); ?>">
            <div class="menu-card">
              <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" alt="<?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?>" />
              <p><?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?></p>
              <?php if($product->offers->count() > 0): ?>
                <span class="product-offer"><?php echo e(__('messages.On Sale')); ?></span>
              <?php endif; ?>
              <p class="product-price"><?php echo e($product->selling_price); ?> JD</p>
            </div>
          </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="no-products">
              <p><?php echo e(__('messages.No products available')); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.menu-tab');
    
    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        // Remove active class from all tabs
        tabs.forEach(t => t.classList.remove('active'));
        
        // Add active class to current tab
        this.classList.add('active');
        
        // Hide all categories
        document.querySelectorAll('.menu-category').forEach(category => {
          category.style.display = 'none';
        });
        
        // Show the selected category
        const categoryId = this.getAttribute('data-category');
        document.getElementById('category-' + categoryId).style.display = 'block';
      });
    });
  });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/menu.blade.php ENDPATH**/ ?>