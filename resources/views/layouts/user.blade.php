<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title')</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{ asset('assets/admin/fonts/SansPro/SansPro.min.css') }}">
    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    @yield('css')
</head>



<body>

<div class="steps-header">
    <div class="container">
        <h1 class="text-center mb-0">{{ __('messages.Create Your Order') }}</h1>
    </div>
</div>

<div class="container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="row">
            <div class="col-md-3 col-6 step active">
                <div class="step-number">1</div>
                <div class="step-title">{{ __('messages.Date & Time') }}</div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">2</div>
                <div class="step-title">{{ __('messages.Select Products') }}</div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">3</div>
                <div class="step-title">{{ __('messages.Review Cart') }}</div>
            </div>
            <div class="col-md-3 col-6 step">
                <div class="step-number">4</div>
                <div class="step-title">{{ __('messages.Checkout') }}</div>
            </div>
        </div>
    </div>

    <form action="{{ route('userOrders.store') }}" method="post" enctype='multipart/form-data'>
        @csrf
        
        <!-- Step 1: Date & Time -->
        <div class="step-content active" id="step1">
            <div class="custom-card card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h3 class="text-center">{{ __('messages.Select Order Date') }} & {{ __('messages.Time') }}</h3>
                </div>
                <div class="card-body">
                    <div class="date-time-picker">
                        <div class="row">
                            <div class="col-md-6 offset-md-3">
                                <label for="order_date" class="form-label fs-5">{{ __('messages.Order Date') }} & {{ __('messages.Time') }}</label>
                                <input type="datetime-local" class="form-control form-control-lg" id="order_date" name="date" 
                                    value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(1)">
                                {{ __('messages.Continue to Products') }} <i class="fas fa-arrow-right ms-2"></i>
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
                    <h3 class="text-center">{{ __('messages.Select Your Products') }}</h3>
                    <div class="d-flex justify-content-center mt-3">
                        <div class="btn-group" role="group" aria-label="Display mode">
                            <button type="button" class="btn btn-outline-primary active" id="available-products-btn">
                                {{ __('messages.Available Products') }}
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="all-products-btn">
                                {{ __('messages.All Products') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="products-loading" class="text-center py-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">{{ __('messages.Loading products...') }}</span>
                        </div>
                        <p class="mt-2 text-muted">{{ __('messages.Loading available products...') }}</p>
                    </div>
                    
                    <div id="products-container" class="product-grid" style="display: none;">
                        <!-- Products will be loaded here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(2)">
                            <i class="fas fa-arrow-left me-2"></i> {{ __('messages.Back') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)" disabled id="review-cart-btn">
                            {{ __('messages.Review Cart') }} <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Review Cart -->
        <div class="step-content" id="step3">
            <div class="custom-card card">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h3 class="text-center">{{ __('messages.Review Your Cart') }}</h3>
                </div>
                <div class="card-body">
                    <div id="cart-items">
                        <!-- Cart items will be displayed here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(3)">
                            <i class="fas fa-arrow-left me-2"></i> {{ __('messages.Back to Products') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(3)">
                            {{ __('messages.Proceed to Checkout') }} <i class="fas fa-arrow-right ms-2"></i>
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
                            <h3>{{ __('messages.Complete Your Order') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">{{ __('messages.Customer Name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('messages.Customer Phone') }}</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                        value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                             
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('messages.Customer Address') }}</label>
                                    <input type="tel" class="form-control" id="address" name="address" 
                                        value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="delivery_id" class="form-label">{{ __('messages.Delivery') }}</label>
                                    <select class="form-select select2" id="delivery_id" name="delivery_id">
                                        <option value="">{{ __('messages.Select Delivery') }}</option>
                                        @foreach($deliveries as $delivery)
                                            <option value="{{ $delivery->id }}" data-price="{{ $delivery->price }}" {{ old('delivery_id') == $delivery->id ? 'selected' : '' }}>
                                                {{ $delivery->place }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('delivery_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="payment_type" class="form-label">{{ __('messages.Payment Type') }}</label>
                                    <select class="form-control" id="payment_type" name="payment_type" required>
                                        <option value="">{{ __('messages.Select Payment Type') }}</option>
                                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>{{ __('messages.Cash') }}</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                               
                            </div>

                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-secondary btn-lg me-3" onclick="previousStep(4)">
                                    <i class="fas fa-arrow-left me-2"></i> {{ __('messages.Back') }}
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check me-2"></i> {{ __('messages.Place Order') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="checkout-summary">
                        <h4 class="mb-3">{{ __('messages.Order Summary') }}</h4>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedProducts = {};
    let currentStep = 1;
    let productsData = [];

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        $('#available-products-btn').on('click', function() {
        $(this).addClass('active');
        $('#all-products-btn').removeClass('active');
        
        // Show Review Cart button
        $('#review-cart-btn').show();
        
        if ($('#order_date').val()) {
            fetchAvailableProducts($('#order_date').val());
        } else {
            $('#products-container').html('<div class="alert alert-info">{{ __("messages.Please select a date first") }}</div>');
        }
     });
        
        $('#all-products-btn').on('click', function() {
            $(this).addClass('active');
            $('#available-products-btn').removeClass('active');
            
            // Hide Review Cart button
            $('#review-cart-btn').hide();
            
            fetchAllProducts();
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
            if (!$('#order_date').val() && $('#available-products-btn').hasClass('active')) {
                $('#order_date').addClass('is-invalid');
                return;
            }
            $('#order_date').removeClass('is-invalid');
            
            // If switching to step 2 and "Available Products" is active, load them
            if ($('#available-products-btn').hasClass('active') && $('#order_date').val()) {
                fetchAvailableProducts($('#order_date').val());
            } else if ($('#all-products-btn').hasClass('active')) {
                // If "All Products" is active, load all products as non-selectable
                fetchAllProducts();
            }
        }
        
        // Don't proceed if in All Products mode and trying to go past step 2
        if (step === 2 && $('#all-products-btn').hasClass('active')) {
            alert('{{ __("messages.Please switch to Available Products mode to select items for purchase") }}');
            return;
        }

        if (step === 2) {
            if (Object.keys(selectedProducts).length === 0) {
                alert('{{ __('messages.Please select at least one product') }}');
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
            url: '{{ route("user.orders.available-products") }}',
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
                alert('{{ __('messages.Error loading products. Please try again') }}');
            }
        });
    }

    // Add this function to fetch all products
   function fetchAllProducts() {
    $('#products-loading').show();
    $('#products-container').hide();

    $.ajax({
        url: '{{ route("user.orders.all-products") }}',
        method: 'GET',
        success: function(response) {
            // Display products as non-selectable
            displayProducts(response.products, false);
            $('#products-loading').hide();
            $('#products-container').show();
        },
        error: function(xhr) {
            $('#products-loading').hide();
            alert('{{ __("messages.Error loading products. Please try again") }}');
        }
    });
}

  function displayProducts(products, selectable = true) {
    const container = $('#products-container');
    container.empty();
    
    if (products.length === 0) {
        container.html('<div class="empty-state"><i class="fas fa-box-open fa-3x mb-3"></i><p>{{ __('messages.No products available for the selected date') }}</p></div>');
        return;
    }
    
    products.forEach(function(product) {
        const discount = product.offer_price ? ((product.selling_price - product.offer_price) / product.selling_price * 100).toFixed(0) : 0;
        
        const productCard = `
            <div class="product-card ${!selectable ? 'not-selectable' : ''}" data-product-id="${product.id}" ${selectable ? `onclick="toggleProduct(${product.id})"` : ''}>
                <div class="product-image-container">
                    <img src="${product.image}" alt="${product.name_en || product.name_ar}" class="product-image">
                    ${discount > 0 ? `<span class="discount-badge">-${discount}%</span>` : ''}
                    <button type="button" class="view-image-btn" onclick="viewFullImage('${product.image}', '${product.name_en || product.name_ar}', event)">
                        <i class="fas fa-search-plus"></i>
                    </button>
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
                ${selectable ? `
                <div class="selected-overlay">
                    <i class="fas fa-check-circle"></i>
                </div>
                ` : ''}
            </div>
        `;
        
        container.append(productCard);
    });
    
    // Restore selected state only if selectable
    if (selectable) {
        Object.keys(selectedProducts).forEach(productId => {
            $(`.product-card[data-product-id="${productId}"]`).addClass('selected');
        });
    }
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
            button.html(`{{ __('messages.Review Cart') }} (${count}) <i class="fas fa-arrow-right ms-2"></i>`);
        } else {
            button.prop('disabled', true);
            button.html('{{ __('messages.Review Cart') }} <i class="fas fa-arrow-right ms-2"></i>');
        }
    }

    function updateCartDisplay() {
        const container = $('#cart-items');
        container.empty();

        if (Object.keys(selectedProducts).length === 0) {
            container.html('<div class="empty-state"><i class="fas fa-shopping-cart fa-3x mb-3"></i><p>{{ __('messages.Your cart is empty') }}</p></div>');
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
                <span>{{ __('messages.Subtotal') }}:</span>
                <span>JD ${subtotal.toFixed(2)}</span>
            </div>
            <div class="summary-item text-success">
                <span>{{ __('messages.Discount') }}:</span>
                <span>-JD ${totalDiscount.toFixed(2)}</span>
            </div>
            <div class="summary-item">
                <span>{{ __('messages.Delivery Fee') }}:</span>
                <span>JD ${deliveryFee.toFixed(2)}</span>
            </div>
            <div class="summary-item total">
                <span>{{ __('messages.Total') }}:</span>
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

   // Updated function for Bootstrap 5
    function viewFullImage(imageUrl, productName, event) {
        // Prevent triggering the toggleProduct function
        event.stopPropagation();
        
        // Create modal if it doesn't exist
        if (!$('#imageModal').length) {
            const modalHtml = `
                <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="" class="img-fluid" id="modalImage" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(modalHtml);
        }
        
        // Set modal content
        $('#imageModal .modal-title').text(productName);
        $('#modalImage').attr('src', imageUrl);
        
        // Show modal using Bootstrap 5 syntax
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

    // Add these styles to your document
    $(document).ready(function() {
        // Add CSS for the view image button
        $('head').append(`
            <style>
                .product-image-container {
                    position: relative;
                }
                
                .view-image-btn {
                    position: absolute;
                    bottom: 10px;
                    right: 10px;
                    background-color: rgba(255, 255, 255, 0.8);
                    border: none;
                    border-radius: 50%;
                    width: 32px;
                    height: 32px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    opacity: 0.7;
                    transition: opacity 0.3s;
                    z-index: 5;
                }
                
                .view-image-btn:hover {
                    opacity: 1;
                }
                
                .product-card:hover .view-image-btn {
                    opacity: 1;
                }
                
                #modalImage {
                    max-height: 80vh;
                }
            </style>
        `);
    });
</script>
</body>
</html>