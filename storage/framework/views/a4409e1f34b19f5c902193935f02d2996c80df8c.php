<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?php echo $__env->yieldContent('title'); ?></title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/plugins/fontawesome-free/css/all.min.css')); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/dist/css/adminlte.min.css')); ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/fonts/SansPro/SansPro.min.css')); ?>">
    <?php if(App::getLocale() == 'ar'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/mycustomstyle.css')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
<style>
  .steps-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
  }
  
  .step-indicator {
      position: relative;
      margin-bottom: 3rem;
  }
  
  .step-indicator::before {
      content: '';
      position: absolute;
      top: 30px;
      left: 16.666%;
      right: 16.666%;
      height: 2px;
      background: #e5e7eb;
      z-index: 1;
  }
  
  .step {
      position: relative;
      text-align: center;
  }
  
  .step-number {
      width: 60px;
      height: 60px;
      margin: 0 auto 1rem;
      background: #fff;
      border: 3px solid #e5e7eb;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1.5rem;
      position: relative;
      z-index: 2;
      transition: all 0.3s ease;
  }
  
  .step.active .step-number {
      background: #667eea;
      color: white;
      border-color: #667eea;
      transform: scale(1.1);
  }
  
  .step.completed .step-number {
      background: #10b981;
      color: white;
      border-color: #10b981;
  }
  
  .step-title {
      font-weight: 600;
      color: #374151;
  }
  
  .step.active .step-title {
      color: #667eea;
  }
  
  .step-content {
      display: none;
      animation: fadeIn 0.5s ease-in-out;
  }
  
  .step-content.active {
      display: block;
  }
  
  .custom-card {
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
      transition: all 0.3s ease;
  }
  
  .custom-card:hover {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }
  
  .date-time-picker {
      background: #f9fafb;
      border-radius: 8px;
      padding: 2rem;
  }
  
  .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 1.5rem;
      margin-top: 2rem;
  }
  
  .product-card {
      border: 3px solid #e5e7eb;
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
  }
  
  .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* Style for selected product cards */
  .product-card.selected {
      border: 3px solid #667eea !important;
      transform: translateY(-4px);
      box-shadow: 0 6px 12px rgba(102, 126, 234, 0.15) !important;
  }
  
  .product-image-container {
      height: 200px;
      overflow: hidden;
      position: relative;
  }
  
  .product-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
  }
  
  .product-card:hover .product-image {
      transform: scale(1.05);
  }
  
  .product-info {
      padding: 1rem;
  }
  
  .product-name {
      font-weight: 600;
      margin-bottom: 0.5rem;
      line-height: 1.4;
  }
  
  .product-prices {
      display: flex;
      align-items: center;
      gap: 1rem;
  }
  
  .price-original {
      text-decoration: line-through;
      color: #9ca3af;
  }
  
  .price-offer {
      color: #ef4444;
      font-weight: bold;
      font-size: 1.1rem;
  }
  
  .discount-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #ef4444;
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: bold;
  }
  
  .selected-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(102, 126, 234, 0.9);
      display: none;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
  }
  
  .product-card.selected .selected-overlay {
      display: flex;
  }
  
  .checkout-summary {
      background: #f9fafb;
      border-radius: 12px;
      padding: 1.5rem;
      position: sticky;
      top: 20px;
  }
  
  .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
      padding: 0.5rem 0;
  }
  
  .summary-item.total {
      border-top: 2px solid #e5e7eb;
      font-weight: bold;
      font-size: 1.1rem;
      color: #111827;
      margin-top: 1rem;
      padding-top: 1rem;
  }
  
  .btn-primary {
      background: #667eea;
      border-color: #667eea;
      transition: all 0.3s ease;
  }
  
  .btn-primary:hover {
      background: #5a67d8;
      border-color: #5a67d8;
  }
  
  .cart-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid #e5e7eb;
  }
  
  .cart-item-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 1rem;
  }
  
  .quantity-controls {
      display: inline-flex;
      align-items: center;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      overflow: hidden;
  }
  
  .quantity-btn {
      padding: 0.5rem 1rem;
      background: #f9fafb;
      border: none;
      cursor: pointer;
      transition: background-color 0.2s;
  }
  
  .quantity-btn:hover {
      background: #e5e7eb;
  }
  
  .quantity-input {
      border: none;
      width: 60px;
      text-align: center;
      padding: 0.5rem;
  }
  
  .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #6b7280;
  }
  
  @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
  }
  
  @media (max-width: 768px) {
      .product-grid {
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 1rem;
      }
      
      .steps-header {
          padding: 1rem 0;
      }
      
      .step-number {
          width: 50px;
          height: 50px;
          font-size: 1.25rem;
      }
  }
</style>
    <?php echo $__env->yieldContent('css'); ?>
</head>



<body>

<div class="steps-header">
    <div class="container">
        <h1 class="text-center mb-0"><?php echo e(__('messages.Create Your Order')); ?></h1>
    </div>
</div>

<div class="container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="row">
            <div class="col-md-3 col-6 step active">
                <div class="step-number">1</div>
                <div class="step-title"><?php echo e(__('messages.Date & Time')); ?></div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">2</div>
                <div class="step-title"><?php echo e(__('messages.Select Products')); ?></div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">3</div>
                <div class="step-title"><?php echo e(__('messages.Review Cart')); ?></div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">4</div>
                <div class="step-title"><?php echo e(__('messages.Checkout')); ?></div>
            </div>
        </div>
    </div>

    <form action="<?php echo e(route('userOrders.store')); ?>" method="post" enctype='multipart/form-data'>
        <?php echo csrf_field(); ?>
        
        <!-- Step 1: Date & Time -->
        <div class="step-content active" id="step1">
            <div class="custom-card card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h3 class="text-center"><?php echo e(__('messages.Select Order Date')); ?> & <?php echo e(__('messages.Time')); ?></h3>
                </div>
                <div class="card-body">
                    <div class="date-time-picker">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="order_date" class="form-label fs-5"><?php echo e(__('messages.Order Date')); ?> & <?php echo e(__('messages.Time')); ?></label>
                                <input type="datetime-local" class="form-control form-control-lg" id="order_date" name="date" 
                                    value="<?php echo e(old('date')); ?>" required>
                                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(1)">
                                <?php echo e(__('messages.Continue to Products')); ?> <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Product Selection -->
        <div class="step-content" id="step2">
            <div class="custom-card card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h3 class="text-center"><?php echo e(__('messages.Select Your Products')); ?></h3>
                </div>
                <div class="card-body">
                    <div id="products-loading" class="text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden"><?php echo e(__('messages.Loading products...')); ?></span>
                        </div>
                        <p class="mt-2 text-muted"><?php echo e(__('messages.Loading available products...')); ?></p>
                    </div>
                    
                    <div id="products-container" class="product-grid" style="display: none;">
                        <!-- Products will be loaded here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(2)">
                            <i class="fas fa-arrow-left me-2"></i> <?php echo e(__('messages.Back')); ?>

                        </button>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)" disabled id="review-cart-btn">
                            <?php echo e(__('messages.Review Cart')); ?> <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Review Cart -->
        <div class="step-content" id="step3">
            <div class="custom-card card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h3 class="text-center"><?php echo e(__('messages.Review Your Cart')); ?></h3>
                </div>
                <div class="card-body">
                    <div id="cart-items">
                        <!-- Cart items will be displayed here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(3)">
                            <i class="fas fa-arrow-left me-2"></i> <?php echo e(__('messages.Back to Products')); ?>

                        </button>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(3)">
                            <?php echo e(__('messages.Proceed to Checkout')); ?> <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Checkout -->
        <div class="step-content" id="step4">
            <div class="row">
                <div class="col-md-7">
                    <div class="custom-card card">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h3><?php echo e(__('messages.Complete Your Order')); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label"><?php echo e(__('messages.Customer Name')); ?></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="<?php echo e(old('name')); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label"><?php echo e(__('messages.Customer Phone')); ?></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                        value="<?php echo e(old('phone')); ?>" required>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="delivery_id" class="form-label"><?php echo e(__('messages.Delivery')); ?></label>
                                    <select class="form-select select2" id="delivery_id" name="delivery_id">
                                        <option value=""><?php echo e(__('messages.Select Delivery')); ?></option>
                                        <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($delivery->id); ?>" data-price="<?php echo e($delivery->price); ?>" <?php echo e(old('delivery_id') == $delivery->id ? 'selected' : ''); ?>>
                                                <?php echo e($delivery->place); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['delivery_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payment_type" class="form-label"><?php echo e(__('messages.Payment Type')); ?></label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value=""><?php echo e(__('messages.Select Payment Type')); ?></option>
                                        <option value="cash" <?php echo e(old('payment_type') == 'cash' ? 'selected' : ''); ?>><?php echo e(__('messages.Cash')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                               
                            </div>

                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(4)">
                                    <i class="fas fa-arrow-left me-2"></i> <?php echo e(__('messages.Back')); ?>

                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check me-2"></i> <?php echo e(__('messages.Place Order')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="checkout-summary">
                        <h4 class="mb-3"><?php echo e(__('messages.Order Summary')); ?></h4>
                        <div id="checkout-summary-content">
                            <!-- Summary content will be populated here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fields for calculated values -->
        <input type="hidden" name="total_prices" id="hidden_total_prices">
        <input type="hidden" name="total_discounts" id="hidden_total_discounts">
        <input type="hidden" name="products_data" id="hidden_products_data">
        <input type="hidden" name="products[]" id="hidden_products">
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let selectedProducts = {};
    let currentStep = 1;
    let productsData = [];

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        // When order date changes, fetch available products
        $('#order_date').on('change', function() {
            var orderDate = $(this).val();
            if (orderDate) {
                fetchAvailableProducts(orderDate);
            }
        });

        // When delivery changes, update totals
        $('#delivery_id').on('change', function() {
            updateCheckoutSummary();
        });
    });

    function nextStep(step) {
        if (step === 1) {
            if (!$('#order_date').val()) {
                $('#order_date').addClass('is-invalid');
                return;
            }
            $('#order_date').removeClass('is-invalid');
        }

        if (step === 2) {
            if (Object.keys(selectedProducts).length === 0) {
                alert('<?php echo e(__('messages.Please select at least one product')); ?>');
                return;
            }
            updateCartDisplay();
        }

        if (step === 3) {
            updateCheckoutSummary();
        }

        currentStep = step + 1;
        updateStepIndicator();
        showStep(currentStep);
    }

    function previousStep(step) {
        currentStep = step - 1;
        updateStepIndicator();
        showStep(currentStep);
    }

    function updateStepIndicator() {
        $('.step').removeClass('active completed');
        for (let i = 1; i <= 4; i++) {
            if (i < currentStep) {
                $(`.step:nth-child(${i})`).addClass('completed');
            } else if (i === currentStep) {
                $(`.step:nth-child(${i})`).addClass('active');
            }
        }
    }

    function showStep(step) {
        $('.step-content').removeClass('active');
        $(`#step${step}`).addClass('active');
    }

    function fetchAvailableProducts(orderDate) {
        $('#products-loading').show();
        $('#products-container').hide();

        $.ajax({
            url: '<?php echo e(route("user.orders.available-products")); ?>',
            method: 'GET',
            data: { date: orderDate },
            success: function(response) {
                productsData = response.products;
                displayProducts(response.products);
                $('#products-loading').hide();
                $('#products-container').show();
            },
            error: function(xhr) {
                $('#products-loading').hide();
                alert('<?php echo e(__('messages.Error loading products. Please try again')); ?>');
            }
        });
    }

    function displayProducts(products) {
        const container = $('#products-container');
        container.empty();

        if (products.length === 0) {
            container.html('<div class="empty-state"><i class="fas fa-box-open fa-3x mb-3"></i><p><?php echo e(__('messages.No products available for the selected date')); ?></p></div>');
            return;
        }

        products.forEach(function(product) {
            const discount = product.offer_price ? ((product.selling_price - product.offer_price) / product.selling_price * 100).toFixed(0) : 0;
            
            const productCard = `
                <div class="product-card" data-product-id="${product.id}" onclick="toggleProduct(${product.id})">
                    <div class="product-image-container">
                        <img src="${product.image}" alt="${product.name_en || product.name_ar}" class="product-image">
                        ${discount > 0 ? `<span class="discount-badge">-${discount}%</span>` : ''}
                    </div>
                    <div class="product-info">
                        <h5 class="product-name">${product.name_en || product.name_ar}</h5>
                        <div class="product-prices">
                            ${product.offer_price ? 
                                `<span class="price-original">JD ${product.selling_price}</span>
                                 <span class="price-offer">JD ${product.offer_price}</span>` :
                                `<span class="price-current">JD ${product.selling_price}</span>`
                            }
                        </div>
                    </div>
                    <div class="selected-overlay">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            `;
            
            container.append(productCard);
        });

        // Restore selected state
        Object.keys(selectedProducts).forEach(productId => {
            $(`.product-card[data-product-id="${productId}"]`).addClass('selected');
        });
    }

    function toggleProduct(productId) {
        const productCard = $(`.product-card[data-product-id="${productId}"]`);
        
        if (selectedProducts[productId]) {
            delete selectedProducts[productId];
            productCard.removeClass('selected');
        } else {
            const product = productsData.find(p => p.id === productId);
            selectedProducts[productId] = {
                ...product,
                quantity: 1
            };
            productCard.addClass('selected');
        }

        updateReviewCartButton();
        updateHiddenFields();
    }

    function updateReviewCartButton() {
        const button = $('#review-cart-btn');
        const count = Object.keys(selectedProducts).length;
        
        if (count > 0) {
            button.prop('disabled', false);
            button.html(`<?php echo e(__('messages.Review Cart')); ?> (${count}) <i class="fas fa-arrow-right ms-2"></i>`);
        } else {
            button.prop('disabled', true);
            button.html('<?php echo e(__('messages.Review Cart')); ?> <i class="fas fa-arrow-right ms-2"></i>');
        }
    }

    function updateCartDisplay() {
        const container = $('#cart-items');
        container.empty();

        if (Object.keys(selectedProducts).length === 0) {
            container.html('<div class="empty-state"><i class="fas fa-shopping-cart fa-3x mb-3"></i><p><?php echo e(__('messages.Your cart is empty')); ?></p></div>');
            return;
        }

        Object.values(selectedProducts).forEach(product => {
            const item = `
                <div class="cart-item">
                    <img src="${product.image}" alt="${product.name_en || product.name_ar}" class="cart-item-image">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${product.name_en || product.name_ar}</h6>
                        <p class="mb-0 text-muted">
                            ${product.offer_price ? 
                                `<span class="text-decoration-line-through me-2">JD ${product.selling_price}</span>
                                 <span class="text-danger fw-bold">JD ${product.offer_price}</span>` :
                                `<span>JD ${product.selling_price}</span>`
                            }
                        </p>
                    </div>
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(${product.id}, -1)">-</button>
                        <input type="number" class="quantity-input" value="${product.quantity}" min="1" 
                            onchange="updateQuantity(${product.id}, 0, this.value)">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(${product.id}, 1)">+</button>
                    </div>
                    <div class="ms-3">
                        <strong>JD ${((product.offer_price || product.selling_price) * product.quantity).toFixed(2)}</strong>
                    </div>
                </div>
            `;
            container.append(item);
        });
    }

    function updateQuantity(productId, change, newValue = null) {
        if (newValue !== null) {
            selectedProducts[productId].quantity = Math.max(1, parseInt(newValue) || 1);
        } else {
            selectedProducts[productId].quantity = Math.max(1, selectedProducts[productId].quantity + change);
        }
        
        updateCartDisplay();
        updateCheckoutSummary();
        updateHiddenFields();
    }

    function updateCheckoutSummary() {
        let subtotal = 0;
        let totalDiscount = 0;
        
        Object.values(selectedProducts).forEach(product => {
            const price = product.selling_price;
            const offerPrice = product.offer_price || price;
            const quantity = product.quantity;
            
            subtotal += offerPrice * quantity;
            totalDiscount += (price - offerPrice) * quantity;
        });

        // Get delivery fee from selected option
        const deliverySelect = $('#delivery_id');
        const selectedOption = deliverySelect.find('option:selected');
        const deliveryFee = selectedOption.data('price') || 0;
        
        const total = subtotal + deliveryFee;

        const summary = `
            <div class="summary-item">
                <span><?php echo e(__('messages.Subtotal')); ?>:</span>
                <span>JD ${subtotal.toFixed(2)}</span>
            </div>
            <div class="summary-item text-success">
                <span><?php echo e(__('messages.Discount')); ?>:</span>
                <span>-JD ${totalDiscount.toFixed(2)}</span>
            </div>
            <div class="summary-item">
                <span><?php echo e(__('messages.Delivery Fee')); ?>:</span>
                <span>JD ${deliveryFee.toFixed(2)}</span>
            </div>
            <div class="summary-item total">
                <span><?php echo e(__('messages.Total')); ?>:</span>
                <span>JD ${total.toFixed(2)}</span>
            </div>
        `;

        $('#checkout-summary-content').html(summary);
        
        // Update hidden fields
        $('#hidden_total_prices').val(subtotal);
        $('#hidden_total_discounts').val(totalDiscount);
    }

    function updateHiddenFields() {
        const productIds = Object.keys(selectedProducts);
        const productsDataArray = [];

        Object.values(selectedProducts).forEach(product => {
            const price = product.selling_price;
            const offerPrice = product.offer_price || price;
            const discount = price - offerPrice;
            const discountPercentage = discount > 0 ? ((discount / price) * 100).toFixed(2) : 0;
            
            productsDataArray.push({
                product_id: product.id,
                quantity: product.quantity,
                unit_price: price,
                offer_price: offerPrice,
                total_price: offerPrice * product.quantity,
                discount_percentage: discountPercentage,
                discount_value: discount * product.quantity
            });
        });

        $('#hidden_products').val(JSON.stringify(productIds));
        $('#hidden_products_data').val(JSON.stringify(productsDataArray));
    }
</script>
</body>
</html><?php /**PATH C:\laragon\www\game\resources\views/layouts/user.blade.php ENDPATH**/ ?>