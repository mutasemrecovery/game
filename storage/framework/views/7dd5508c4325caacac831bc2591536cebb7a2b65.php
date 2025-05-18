<header class="navbar">
  <div class="container">
    
    <div class="logo">
      <a href="<?php echo e(route('home')); ?>">
        <img src="<?php echo e(asset('assets_front/assets/images/logo.png')); ?>" alt="The Dynamite Box Logo" />
      </a>
    </div>

    <button class="navbar-toggle" id="navbar-toggle">
      <i class="fa fa-bars"></i>
    </button>

    <div class="nav-wrapper" id="nav-wrapper">
      <nav class="nav-links">
        <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>"><?php echo e(__('messages.Home')); ?></a>

        <div class="menu-dropdown-container">
          <a href="#" class="menu-toggle-link"><?php echo e(__('messages.Menu')); ?> <i class="fa fa-chevron-down"></i></a>
          <div class="menu-dropdown-panel">
            <div class="menu-dropdown-grid">
              <?php
              $products = App\Models\Product::limit(6)->get();    
              $locale = App::getLocale();
              ?>
              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <!-- Menu items -->
              <a href="<?php echo e(route('product.details', $product->id)); ?>">
                <div class="menu-dropdown-item">
                  <?php if($product->productImages && $product->productImages->first()): ?>
                    <img src="<?php echo e(asset('assets/admin/uploads/' . $product->productImages->first()->photo)); ?>" alt="<?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?>">
                  <?php endif; ?>
                  <span><?php echo e($locale == 'en' ? $product->name_en : $product->name_ar); ?></span>
                </div>
              </a>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="menu-dropdown-footer">
              <a href="<?php echo e(route('menu')); ?>" class="menu-view-full-btn"><?php echo e(__('messages.View Full Menu')); ?></a>
            </div>
          </div>
        </div>

        <a href="" class=""><?php echo e(__('messages.Our Branches')); ?></a>
        <a href="" class=""><?php echo e(__('messages.About')); ?></a>
        <a href="" class="franchise-btn "><?php echo e(__('messages.Franchise')); ?></a>
      </nav>
    </div>

    <!-- Icons section -->
    <div class="icons">
      <?php if(Auth::check()): ?>
        <div class="user-menu-container">
          <i class="fa fa-user" id="user-toggle"></i>
          <div class="user-dropdown" id="user-dropdown">
            <div class="user-info">
              <img src="<?php echo e(asset('assets_front/assets/images/boy.png')); ?>" alt="User Profile" />
              <span><?php echo e(Auth::user()->name); ?></span>
            </div>
            <ul class="user-links">
              <li><a href="<?php echo e(route('profile')); ?>"><?php echo e(__('messages.My Account')); ?></a></li>
              <li><a href="<?php echo e(route('orders')); ?>"><?php echo e(__('messages.My Orders')); ?></a></li>
              <li>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="logout-btn"><?php echo e(__('messages.Logout')); ?></button>
                </form>
              </li>
            </ul>
          </div>
        </div>

        <div class="cart-container">
          <a href="<?php echo e(route('cart.index')); ?>">
            <i class="fa fa-shopping-cart cart-icon"></i>
            <?php
              $cartCount = App\Models\Cart::where('user_id', Auth::id())->where('status', 0)->count();
            ?>
            <?php if($cartCount > 0): ?>
              <span class="cart-badge"><?php echo e($cartCount); ?></span>
            <?php endif; ?>
          </a>
          <div class="cart-dropdown">
            <p><strong><?php echo e(__('messages.Cart')); ?></strong></p>
            <hr />
            <?php
              $cartItems = App\Models\Cart::where('user_id', Auth::id())
                            ->where('status', 0)
                            ->with(['product'])
                            ->limit(3)
                            ->get();
              $subtotal = $cartItems->sum('total_price_product');
            ?>
            
            <?php $__empty_1 = true; $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <div class="cart-item">
                <?php if($item->product && $item->product->productImages && $item->product->productImages->first()): ?>
                  <img src="<?php echo e(asset('assets/admin/uploads/' . $item->product->productImages->first()->photo)); ?>" alt="<?php echo e($item->product->name_en); ?>" />
                <?php else: ?>
                  <img src="<?php echo e(asset('assets_front/assets/images/placeholder.png')); ?>" alt="Product" />
                <?php endif; ?>
                <div class="item-info">
                  <p><?php echo e($locale == 'en' ? $item->product->name_en : $item->product->name_ar); ?></p>
                  <span>x<?php echo e($item->quantity); ?></span>
                </div>
                <div class="item-price"><?php echo e($item->price); ?> JD</div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="empty-cart">
                <p><?php echo e(__('messages.Your cart is empty')); ?></p>
              </div>
            <?php endif; ?>
            
            <?php if(count($cartItems) > 0): ?>
              <div class="cart-subtotal">
                <span><?php echo e(__('messages.Subtotal')); ?>:</span>
                <strong><?php echo e($subtotal); ?> JD</strong>
              </div>
              <a href="<?php echo e(route('cart.index')); ?>" class="view-cart-btn"><?php echo e(__('messages.View Cart')); ?></a>
            <?php endif; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="auth-links">
          <a href="<?php echo e(route('login')); ?>"><strong><?php echo e(__('messages.Login')); ?></strong></a> / 
          <a href="<?php echo e(route('register')); ?>"><strong><?php echo e(__('messages.Register')); ?></strong></a>
        </div>
      <?php endif; ?>
      
      <div class="language-selector">
        <i class="fa fa-globe" id="lang-toggle"></i>
        <div class="lang-dropdown" id="lang-dropdown">
          <p><?php echo e(__('messages.Language')); ?></p>
          <hr />
          <?php $__currentLoopData = LaravelLocalization::getSupportedLocales(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localeCode => $properties): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="nav-link <?php echo e(App::getLocale() == $localeCode ? 'active' : ''); ?>" 
               hreflang="<?php echo e($localeCode); ?>" 
               href="<?php echo e(LaravelLocalization::getLocalizedURL($localeCode, null, [], true)); ?>">
              <label><?php echo e($properties['native']); ?></label>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const navbarToggle = document.getElementById('navbar-toggle');
    const navWrapper = document.getElementById('nav-wrapper');
    
    navbarToggle.addEventListener('click', function() {
      navWrapper.classList.toggle('active');
    });
    
    // User dropdown toggle
    const userToggle = document.getElementById('user-toggle');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userToggle && userDropdown) {
      userToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
        
        // Hide language dropdown if open
        if (langDropdown && langDropdown.classList.contains('show')) {
          langDropdown.classList.remove('show');
        }
      });
    }
    
    // Language dropdown toggle
    const langToggle = document.getElementById('lang-toggle');
    const langDropdown = document.getElementById('lang-dropdown');
    
    if (langToggle && langDropdown) {
      langToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        langDropdown.classList.toggle('show');
        
        // Hide user dropdown if open
        if (userDropdown && userDropdown.classList.contains('show')) {
          userDropdown.classList.remove('show');
        }
      });
    }
    
    // Hide dropdowns when clicking elsewhere
    document.addEventListener('click', function() {
      if (userDropdown && userDropdown.classList.contains('show')) {
        userDropdown.classList.remove('show');
      }
      
      if (langDropdown && langDropdown.classList.contains('show')) {
        langDropdown.classList.remove('show');
      }
    });
    
    // Prevent dropdown from closing when clicking inside it
    if (userDropdown) {
      userDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
    
    if (langDropdown) {
      langDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
  });
</script><?php /**PATH C:\laragon\www\dynamite\resources\views/user/includes/navbar.blade.php ENDPATH**/ ?>