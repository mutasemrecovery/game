

<?php $__env->startSection('content'); ?>
<section class="product-details-section">
    <div class="product-details-container">
      <div class="product-image-gallery">
        <?php if($product->productImages->count() > 0): ?>
          <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" alt="<?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?>" class="main-product-image" id="mainProductImage">
        
          <div class="product-thumbnails">
            <?php $__currentLoopData = $product->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <img src="<?php echo e(asset('assets/admin/uploads/' . $image->photo)); ?>" alt="Thumbnail <?php echo e($index + 1); ?>" class="thumbnail <?php echo e($index == 0 ? 'active-thumb' : ''); ?>" onclick="changeMainImage(this)">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php else: ?>
          <img src="<?php echo e(asset('assets/images/placeholder.png')); ?>" alt="No image available" class="main-product-image">
        <?php endif; ?>
      </div>
  
      <div class="product-info">
        <div class="product-title-price">
          <h2 class="product-name"><?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?></h2>
          <span class="product-price">
            <?php echo e($product->selling_price); ?> JD
            <?php if($product->offers->count() > 0): ?>
              <span class="product-offer"><?php echo e(__('messages.On Sale')); ?></span>
            <?php endif; ?>
          </span>
        </div>
  
        <p class="product-description">
          <?php echo e($locale == 'en' ? $product->description_en : $product->description_ar); ?>

        </p>
  
        <div class="product-rating">
          <span><?php echo e(__('messages.Rating')); ?></span>
          <div class="stars">
            ★★★★☆
          </div>
        </div>
  
        <div class="product-action">
          <div class="quantity-selector">
            <div class="quantity-box">
              <button id="decreaseQuantity" onclick="updateQuantity(-1)">-</button>
              <span id="quantityValue">1</span>
              <button id="increaseQuantity" onclick="updateQuantity(1)">+</button>
            </div>              
          </div>
  
          <button class="add-to-cart-btn" onclick="addToCart(<?php echo e($product->id); ?>)"><?php echo e(__('messages.Add to Cart')); ?></button>
        </div>
      </div>
    </div>
  </section>
  
  <section class="popular-section">
    <div class="popular-row">
      <div class="popular-bg-pattern"></div>
  
      <div class="container popular-content">
        <div class="popular-header">
          <h2 class="popular-title"><?php echo e(__('messages.Similar Food')); ?></h2>
        </div>
  
        <div class="view-more-container">
          <a href="<?php echo e(route('menu')); ?>" class="view-more"><?php echo e(__('messages.View More')); ?></a>
        </div>
  
        <!-- First row -->
        <div class="popular-grid-info">
          <?php $__currentLoopData = $similarProducts->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="popular-card-info">
              <?php if($similar->productImages->count() > 0): ?>
                <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" alt="<?php echo e($locale == 'en' ? $similar->name_en : $similar->name_ar); ?>">
              <?php else: ?>
                <img src="<?php echo e(asset('assets/images/placeholder.png')); ?>" alt="No image available">
              <?php endif; ?>
              <h4><?php echo e($locale == 'en' ? $similar->name_en : $similar->name_ar); ?></h4>
              <p class="price"><?php echo e($similar->selling_price); ?> <?php echo e(__('messages.Currency')); ?></p>
              <div class="card-footer">
                <div class="rating">⭐ 4.8</div>
                <button class="add-btn" onclick="quickAddToCart(<?php echo e($similar->id); ?>)">+</button>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>

    <!-- Second row (if there are more than 4 similar products) -->
    <?php if($similarProducts->count() > 4): ?>
    <div class="popular-row">
      <div class="popular-bg-pattern"></div> 
  
      <div class="container popular-content">
        <div class="popular-grid-info">
          <?php $__currentLoopData = $similarProducts->skip(4)->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="popular-card-info">
              <?php if($similar->productImages->count() > 0): ?>
                <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" alt="<?php echo e($locale == 'en' ? $similar->name_en : $similar->name_ar); ?>">
              <?php else: ?>
                <img src="<?php echo e(asset('assets/images/placeholder.png')); ?>" alt="No image available">
              <?php endif; ?>
              <h4><?php echo e($locale == 'en' ? $similar->name_en : $similar->name_ar); ?></h4>
              <p class="price"><?php echo e($similar->selling_price); ?> <?php echo e(__('messages.Currency')); ?></p>
              <div class="card-footer">
                <div class="rating">⭐ 4.8</div>
                <button class="add-btn" onclick="quickAddToCart(<?php echo e($similar->id); ?>)">+</button>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // Function to change main product image when thumbnail is clicked
  function changeMainImage(element) {
    // Update main image src
    document.getElementById('mainProductImage').src = element.src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
      thumb.classList.remove('active-thumb');
    });
    element.classList.add('active-thumb');
  }
  
  // Function to update quantity
  function updateQuantity(change) {
    const quantityElement = document.getElementById('quantityValue');
    let quantity = parseInt(quantityElement.textContent);
    
    quantity += change;
    
    // Ensure quantity doesn't go below 1
    if (quantity < 1) quantity = 1;
    
    quantityElement.textContent = quantity;
  }
  
  // Function to add product to cart
  function addToCart(productId) {
     // Check if user is authenticated
    const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
    
    if (!isAuthenticated) {
      // Redirect to login page if not authenticated
      window.location.href = '<?php echo e(route("user.login")); ?>';
      return;
    }

    const quantity = parseInt(document.getElementById('quantityValue').textContent);
    
    // AJAX call to add to cart endpoint
    fetch('<?php echo e(route("cart.add")); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success message or update cart count
        alert('<?php echo e(__("messages.Product added to cart")); ?>');
      } else {
        alert('<?php echo e(__("messages.Error adding product to cart")); ?>');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
  
  // Function to quickly add one item to cart from similar products
  function quickAddToCart(productId) {
     // Check if user is authenticated
     const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
    
    if (!isAuthenticated) {
      // Redirect to login page if not authenticated
      window.location.href = '<?php echo e(route("user.login")); ?>';
      return;
    }
    // AJAX call to add to cart endpoint with quantity 1
    fetch('<?php echo e(route("cart.add")); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: 1
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success message or update cart count
        alert('<?php echo e(__("messages.Product added to cart")); ?>');
      } else {
        alert('<?php echo e(__("messages.Error adding product to cart")); ?>');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/product-info.blade.php ENDPATH**/ ?>