

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="hero-slide active">
                    <img src="<?php echo e(asset('assets/admin/uploads/' . $banner->photo)); ?>" alt="Slide 1" class="hero-image" />
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <button class="hero-prev"><i class="fa fa-chevron-left"></i></button>
            <button class="hero-next"><i class="fa fa-chevron-right"></i></button>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>


    <section class="popular-section">
        <div class="popular-row">

            <div class="popular-bg-pattern"></div>

            <div class="container popular-content">
                <div class="popular-header">
                    <h2 class="popular-title"><?php echo e(__('messages.Most popular')); ?></h2>
                </div>

                <div class="view-more-container">
                    <a href="<?php echo e(route('menu')); ?>" class="view-more"><?php echo e(__('messages.View More')); ?></a>
                </div>

                <!-- الصف الأول هنا -->
                <div class="popular-grid">
                    <?php $__currentLoopData = $populars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('product.details', $popular->id)); ?>">
                            <div class="popular-card">
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $popular->productImages->first()->photo)); ?>"
                                    alt="<?php echo e($locale === 'ar' ? $popular->name_ar : $popular->name_en); ?>" />
                                <h4><?php echo e($locale === 'ar' ? $popular->name_ar : $popular->name_en); ?></h4>
                                <p class="price"><?php echo e($popular->selling_price); ?>JD</p>
                                <div class="card-footer">
                                    <div class="rating">⭐ 4.8</div>

                                    <!-- Updated button with auth check -->
                                    <?php if(auth()->guard()->check()): ?>
                                        <button class="add-btn"
                                            onclick="addToCart(<?php echo e($popular->id); ?>, 1); return false;">+</button>
                                    <?php else: ?>
                                        <button class="add-btn" onclick="showLoginPrompt(); return false;">+</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>


            </div>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>


    <section class="branches-section">
        <div class="branches-container">
            <div class="branches-title-row">
                <h2 class="branches-title"><?php echo e(__('messages.Our Branches')); ?></h2>
            </div>

            <div class="branches-cards">
                <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="#" class="branch-card">
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $branch->photo)); ?>" alt="Al-Muqabalin">
                        <p><?php echo e($branch->name); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>



    <section class="appetizer-hero">
        <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="hero-slide active">
                <img src="<?php echo e(asset('assets/admin/uploads/' . $banner->photo)); ?>" alt="Slide 1" class="hero-image" />
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </section>


    <div class="hero-divider">
        <div class="line"></div>
    </div>


    <section class="contact-section">
        <div class="contact-container">
            <h2 class="contact-title"><?php echo e(__('messages.Contact Us')); ?></h2>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="<?php echo e(route('contact.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="contact-row">
                    <input type="text" name="name" placeholder="<?php echo e(__('messages.Name')); ?>" class="contact-input"
                        required />
                    <input type="email" name="email" placeholder="<?php echo e(__('messages.Email')); ?>" class="contact-input"
                        required />
                </div>

                <input type="text" name="subject" placeholder="<?php echo e(__('messages.Subject')); ?>" class="contact-input-full"
                    required />

                <textarea name="message" placeholder="<?php echo e(__('messages.Message')); ?>" class="contact-textarea" required></textarea>

                <button type="submit" class="contact-btn"><?php echo e(__('messages.Send')); ?></button>
            </form>
        </div>
    </section>


    <div class="hero-divider">
        <div class="line"></div>
    </div>


    <section class="about-section">
        <div class="about-container">
            <h2 class="about-title"><?php echo e(__('messages.About Us')); ?></h2>

            <p class="about-intro"><?php echo e($locale === 'ar' ? $page->title_ar : $page->title_en); ?></p>

            <p class="about-description">
                <?php echo $locale === 'ar' ? $page->content_ar : $page->content_en; ?>

            </p>
        </div>
    </section>


    <section class="decorative-divider"></section>

    <?php $__env->startPush('scripts'); ?>
        <!-- Add this JavaScript to the bottom of your page or in a separate JS file -->
        <script>
            function addToCart(productId, quantity) {
                // Prevent default link behavior
                event.preventDefault();

                // Send AJAX request to add to cart
                fetch('<?php echo e(route('cart.add')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            offer_id: null // Set this dynamically if needed
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert(data.message);
                            // Update cart count if needed
                            // updateCartCount(data.cart_count);
                        } else {
                            // Show error message
                            alert(data.message);
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                    });
            }

            function showLoginPrompt() {
                // Prevent default link behavior
                event.preventDefault();

                // Show login prompt
                alert('Please login to add items to cart');
                // Redirect to login page
                window.location.href = '<?php echo e(route('user.login')); ?>';
            }
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/home.blade.php ENDPATH**/ ?>