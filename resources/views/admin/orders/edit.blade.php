@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.orders') }}
@endsection

@section('css')
<style>
/* Selected item text inside multiple select2 */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #000 !important; /* black text */
    background-color: #e9ecef !important; /* light background for contrast (optional) */
    border: 1px solid #ced4da !important;  /* optional for better border visibility */
}

.discount-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.discount-controls select,
.discount-controls input {
    flex: 1;
}

.discount-type-select {
    max-width: 120px;
}
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.orders') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('orders.update', $order->id) }}" method="post" enctype='multipart/form-data'>
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Order Date and Time -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_date">{{ __('messages.Order Date') }}</label>
                            <input type="datetime-local" class="form-control" id="order_date" name="date" 
                                value="{{ old('date', date('Y-m-d\TH:i', strtotime($order->date))) }}" required>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Customer Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">{{ __('messages.Customer') }}</label>
                            <select class="form-control select2 " id="user_id" name="user_id" required>
                                <option value="">{{ __('messages.Select Customer') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Products Selection -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="products">{{ __('messages.Products') }}</label>
                            <select class="form-control select2" id="products" name="products[]" multiple required>
                                <!-- Products will be loaded via AJAX but pre-selected -->
                            </select>
                            @error('products')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Discount Section -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('messages.Additional Order Discount') }}</label>
                            <div class="discount-controls">
                                <select class="form-control discount-type-select" id="additional_discount_type">
                                    <option value="none">{{ __('messages.No Discount') }}</option>
                                    <option value="percentage">{{ __('messages.Percentage') }} (%)</option>
                                    <option value="fixed">{{ __('messages.Fixed Amount') }}</option>
                                </select>
                                <input type="number" class="form-control" id="additional_discount_value" 
                                    min="0" step="0.01" value="0"
                                    placeholder="{{ __('messages.Discount Value') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="delivery_id">{{ __('messages.Delivery') }}</label>
                            <select class="form-control select2" id="delivery_id" name="delivery_id">
                                <option value="">{{ __('messages.Select Delivery') }}</option>
                                @foreach($deliveries as $delivery)
                                    <option value="{{ $delivery->id }}" {{ old('delivery_id', $order->delivery_id) == $delivery->id ? 'selected' : '' }}>
                                        {{ $delivery->place }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_type">{{ __('messages.Payment Type') }}</label>
                            <select class="form-control" id="payment_type" name="payment_type" required>
                                <option value="">{{ __('messages.Select Payment Type') }}</option>
                                <option value="cash" {{ old('payment_type', $order->payment_type) == 'cash' ? 'selected' : '' }}>
                                    {{ __('messages.Cash') }}
                                </option>
                                <option value="card" {{ old('payment_type', $order->payment_type) == 'card' ? 'selected' : '' }}>
                                    {{ __('messages.Card') }}
                                </option>
                                <option value="transfer" {{ old('payment_type', $order->payment_type) == 'transfer' ? 'selected' : '' }}>
                                    {{ __('messages.Bank Transfer') }}
                                </option>
                            </select>
                            @error('payment_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_status">{{ __('messages.Payment Status') }}</label>
                            <select class="form-control" id="payment_status" name="payment_status" required>
                                <option value="2" {{ old('payment_status', $order->payment_status) == 2 ? 'selected' : '' }}>
                                    {{ __('messages.Unpaid') }}
                                </option>
                                <option value="1" {{ old('payment_status', $order->payment_status) == 1 ? 'selected' : '' }}>
                                    {{ __('messages.Paid') }}
                                </option>
                            </select>
                            @error('payment_status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_status">{{ __('messages.Order Status') }}</label>
                            <select class="form-control" id="order_status" name="order_status" required>
                                <option value="1" {{ old('order_status', $order->order_status) == 1 ? 'selected' : '' }}>
                                    {{ __('messages.Pending') }}
                                </option>
                                <option value="2" {{ old('order_status', $order->order_status) == 2 ? 'selected' : '' }}>
                                    {{ __('messages.Processing') }}
                                </option>
                                <option value="3" {{ old('order_status', $order->order_status) == 3 ? 'selected' : '' }}>
                                    {{ __('messages.Cancelled') }} 
                                </option>
                                <option value="6" {{ old('order_status', $order->order_status) == 4 ? 'selected' : '' }}>
                                   {{ __('messages.Completed') }}
                                </option>
                            </select>
                            @error('order_status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Selected Products Summary -->
                    <div class="col-md-12">
                        <div id="selected-products-summary">
                            <h4>{{ __('messages.Selected Products') }}</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.Product') }}</th>
                                        <th>{{ __('messages.Quantity') }}</th>
                                        <th>{{ __('messages.Unit Price') }}</th>
                                        <th>{{ __('messages.Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-products-table">
                                    <!-- Dynamic content will be loaded via JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">{{ __('messages.Subtotal') }}</th>
                                        <th id="subtotal">0.00</th>
                                    </tr>
                               
                                    <tr>
                                        <th colspan="4">{{ __('messages.Additional Discount') }}</th>
                                        <th id="additional-discount-amount">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">{{ __('messages.Delivery Fee') }}</th>
                                        <th id="total-delivery-fee">{{ number_format($order->delivery_fee, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">{{ __('messages.Final Total') }}</th>
                                        <th id="final-total">{{ number_format($order->total_prices + $order->delivery_fee, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Hidden fields for calculated values -->
                    <input type="hidden" name="total_prices" id="hidden_total_prices" value="{{ $order->total_prices }}">
                    <input type="hidden" name="total_discounts" id="hidden_total_discounts" value="{{ $order->total_discounts }}">
                    <input type="hidden" name="products_data" id="hidden_products_data">

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit"
                                class="btn btn-primary btn-sm">{{ __('messages.Update') }}</button>
                            <a href="{{ route('orders.index') }}"
                                class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();
            
            // Load the existing order products directly
            initializeOrderProducts();
            
            // Load existing additional discount if any
            loadExistingAdditionalDiscount();
            
            // Trigger initial total calculation to display the loaded discount
            updateTotals();
            
            // When additional discount changes
            $('#additional_discount_type, #additional_discount_value').on('change input', function() {
                updateTotals();
            });
            
            // When order date changes, we might want to load new products
            $('#order_date').on('change', function() {
                // You might want to prompt the user before changing the date
                // as it could affect product availability
                if (confirm("{{ __('messages.Changing the date may affect product availability. Continue?') }}")) {
                    var orderDate = $(this).val();
                    if (orderDate) {
                        fetchAvailableProducts(orderDate);
                    }
                } else {
                    // Reset to original date
                    $(this).val('{{ date('Y-m-d\TH:i', strtotime($order->date)) }}');
                }
            });

            // When products are selected, update the summary
            $('#products').on('change', function() {
                updateProductsSummary();
            });

            // When delivery changes, update total
            $('#delivery_id').on('change', function() {
                var deliveryId = $(this).val();
                if (deliveryId) {
                    $.ajax({
                        url: '{{ route("deliveries.get-price") }}',
                        method: 'GET',
                        data: { id: deliveryId },
                        success: function(response) {
                            var deliveryFee = parseFloat(response.price);
                            $('#total-delivery-fee').text(deliveryFee.toFixed(2));
                            updateTotals();
                        }
                    });
                } else {
                    $('#total-delivery-fee').text('0.00');
                    updateTotals();
                }
            });

            function loadExistingAdditionalDiscount() {
                // Try to load from URL parameters (if passed from the index page)
                var urlParams = new URLSearchParams(window.location.search);
                var discountType = urlParams.get('discount_type');
                var discountValue = urlParams.get('discount_value');
                
                if (discountType && discountValue) {
                    $('#additional_discount_type').val(discountType);
                    $('#additional_discount_value').val(discountValue);
                    return;
                }
                
                // Fallback: Calculate if there was an additional discount applied
                var orderTotalDiscounts = {{ $order->total_discounts }};
                var orderTotalPrices = {{ $order->total_prices }};
                var orderDeliveryFee = {{ $order->delivery_fee }};
                
                // Calculate product discounts from order products
                var orderProducts = {!! json_encode($orderProducts) !!};
                var calculatedProductDiscounts = 0;
                var calculatedSubtotal = 0;
                
                orderProducts.forEach(function(orderProduct) {
                    var productDiscount = parseFloat(orderProduct.discount_value) || 0;
                    calculatedProductDiscounts += productDiscount;
                    
                    // Calculate what the subtotal should be (price * quantity)
                    var originalPrice = parseFloat(orderProduct.unit_price);
                    var quantity = parseInt(orderProduct.quantity);
                    calculatedSubtotal += (originalPrice * quantity);
                });
                
                // Calculate if there's an additional discount
                var additionalDiscount = orderTotalDiscounts - calculatedProductDiscounts;
                
                if (additionalDiscount > 0.01) { // Small threshold for floating point precision
                    // Try to determine if it was percentage or fixed
                    var subtotalAfterProductDiscounts = calculatedSubtotal - calculatedProductDiscounts;
                    var percentageDiscount = (additionalDiscount / subtotalAfterProductDiscounts) * 100;
                    
                    // If the percentage is a round number (like 5%, 10%, 15%), assume it was percentage
                    if (Math.abs(percentageDiscount - Math.round(percentageDiscount)) < 0.1 && percentageDiscount <= 100) {
                        $('#additional_discount_type').val('percentage');
                        $('#additional_discount_value').val(Math.round(percentageDiscount));
                    } else {
                        // Otherwise assume it was fixed amount
                        $('#additional_discount_type').val('fixed');
                        $('#additional_discount_value').val(additionalDiscount.toFixed(2));
                    }
                } else {
                    $('#additional_discount_type').val('none');
                    $('#additional_discount_value').val(0);
                }
            }

function initializeOrderProducts() {
    var productsSelect = $('#products');
    productsSelect.empty();
    
    // Get existing order products
    var orderProducts = {!! json_encode($orderProducts) !!};
    var orderDate = $('#order_date').val();
    var orderId = {{ $order->id }}; // Get current order ID
    
    // Load all available products via AJAX
    $.ajax({
        url: '{{ route("orders.available-products") }}',
        method: 'GET',
        data: { 
            date: orderDate,
            order_id: orderId // Pass current order ID
        },
        success: function(response) {
            if (response.products && response.products.length > 0) {
                // Add all products to dropdown
                response.products.forEach(function(product) {
                    // Check if this product was in the original order
                    var orderProduct = orderProducts.find(function(op) {
                        return op.product_id == product.id;
                    });
                    
                    var isSelected = orderProduct ? true : false;
                    
                    var option = new Option(
                        product.name_en + ' - ' + product.selling_price,
                        product.id,
                        isSelected,
                        isSelected
                    );
                    
                    // If this product was in the order, use the order data
                    // Otherwise use the current product data
                    if (orderProduct) {
                        $(option).attr('data-price', orderProduct.unit_price);
                        $(option).attr('data-offer-price', 
                            orderProduct.unit_price - (orderProduct.unit_price * orderProduct.discount_percentage / 100));
                    } else {
                        $(option).attr('data-price', product.selling_price);
                        $(option).attr('data-offer-price', product.offer_price || product.selling_price);
                    }
                    
                    productsSelect.append(option);
                });
                
                // Trigger Select2 update
                productsSelect.trigger('change');
                
                // Load the summary table with existing order data
                loadOrderProductsSummary(orderProducts);
            }
        },
        error: function(xhr) {
            console.error('Error loading all products, falling back to order products only:', xhr);
            
            // Fallback: Your original working code
            orderProducts.forEach(function(orderProduct) {
                var product = orderProduct.product;
                var option = new Option(
                    product.name_en + ' - ' + product.selling_price,
                    product.id,
                    true,
                    true
                );
                $(option).attr('data-price', orderProduct.unit_price);
                $(option).attr('data-offer-price', 
                    orderProduct.unit_price - (orderProduct.unit_price * orderProduct.discount_percentage / 100));
                productsSelect.append(option);
            });
            
            // Load the summary table
            loadOrderProductsSummary(orderProducts);
        }
    });
}
            
            function loadOrderProductsSummary(orderProducts) {
                var tableBody = $('#selected-products-table');
                tableBody.empty();
                
                orderProducts.forEach(function(orderProduct) {
                    var product = orderProduct.product;
                    var price = parseFloat(orderProduct.unit_price);
                    var discountPercentage = parseFloat(orderProduct.discount_percentage) || 0;
                    var discountedPrice = price - (price * discountPercentage / 100);
                    
                    var row = `
                        <tr>
                            <td>${product.name_en} - ${price.toFixed(2)}</td>
                            <td>
                                <input type="number" class="form-control quantity" min="1" value="${orderProduct.quantity}" 
                                    data-product-id="${product.id}" 
                                    data-price="${price}" 
                                    data-offer-price="${discountedPrice}">
                            </td>
                            <td>${price.toFixed(2)}</td>
                            <td class="total-cell">${orderProduct.total_price}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
                
                // Show the summary
                $('#selected-products-summary').show();
                
                // Add event listener for quantity changes
                $('.quantity').on('input', function() {
                    updateRowTotal(this);
                    updateTotals();
                });
                
                // Don't call updateTotals here yet - wait for discount to be loaded
            }

            function fetchAvailableProducts(orderDate) {
                var orderId = {{ $order->id }};
                $.ajax({
                    url: '{{ route("orders.available-products") }}',
                    method: 'GET',
                     data: { 
                            date: orderDate,
                            order_id: orderId
                        },
                    success: function(response) {
                        var productsSelect = $('#products');
                        productsSelect.empty();
                        
                        if (response.products && response.products.length > 0) {
                            response.products.forEach(function(product) {
                                var option = new Option(
                                    product.name_en + ' - ' + product.selling_price,
                                    product.id,
                                    false,
                                    false
                                );
                                $(option).attr('data-price', product.selling_price);
                                $(option).attr('data-offer-price', product.offer_price || product.selling_price);
                                productsSelect.append(option);
                            });
                            
                            // Get the product IDs from the order
                            var orderProducts = {!! json_encode($order->orderProducts->pluck('product_id')->toArray()) !!};
                            
                            // Pre-select the products that were already in the order (if they're still available)
                            productsSelect.val(orderProducts).trigger('change');
                            
                            // Update the product summary
                            updateProductsSummary();
                        } else {
                            alert('No products available for the selected date.');
                        }
                    },
                    error: function(xhr) {
                        alert('Error loading products. Please try again.');
                    }
                });
            }

            function updateProductsSummary() {
                var selectedProducts = $('#products').select2('data');
                var tableBody = $('#selected-products-table');
                var summary = $('#selected-products-summary');
                
                if (selectedProducts.length > 0) {
                    summary.show();
                    tableBody.empty();
                    
                    selectedProducts.forEach(function(product) {
                        var productId = product.id;
                        var price = parseFloat($(product.element).attr('data-price'));
                        var offerPrice = parseFloat($(product.element).attr('data-offer-price'));
                        var discount = price - offerPrice;
                        var discountPercentage = discount > 0 ? ((discount / price) * 100).toFixed(2) : 0;
                        
                        // Check if this product was already in the order
                        var orderProduct = {!! json_encode($order->orderProducts) !!}.find(op => op.product_id == productId);
                        var quantity = orderProduct ? orderProduct.quantity : 1;
                        
                        var row = `
                            <tr>
                                <td>${product.text}</td>
                                <td>
                                    <input type="number" class="form-control quantity" min="1" value="${quantity}" 
                                        data-product-id="${productId}" 
                                        data-price="${price}" 
                                        data-offer-price="${offerPrice}">
                                </td>
                                <td>${price.toFixed(2)}</td>
                                <td class="total-cell">${(quantity * offerPrice).toFixed(2)}</td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                    
                    // Add event listener for quantity changes
                    $('.quantity').on('input', function() {
                        updateRowTotal(this);
                        updateTotals();
                    });
                    
                    updateTotals();
                } else {
                    summary.hide();
                }
            }

            function updateRowTotal(quantityInput) {
                var $row = $(quantityInput).closest('tr');
                var quantity = parseInt($(quantityInput).val()) || 0;
                var offerPrice = parseFloat($(quantityInput).attr('data-offer-price'));
                var total = quantity * offerPrice;
                
                $row.find('.total-cell').text(total.toFixed(2));
            }

            function calculateAdditionalDiscount(subtotal) {
                var discountType = $('#additional_discount_type').val();
                var discountValue = parseFloat($('#additional_discount_value').val()) || 0;
                var additionalDiscount = 0;
                
                if (discountType === 'percentage' && discountValue > 0) {
                    additionalDiscount = (subtotal * discountValue) / 100;
                } else if (discountType === 'fixed' && discountValue > 0) {
                    additionalDiscount = Math.min(discountValue, subtotal); // Don't exceed subtotal
                }
                
                return additionalDiscount;
            }

            function updateTotals() {
                var subtotal = 0;
                var totalProductDiscount = 0;
                var productsData = [];
                
                $('.quantity').each(function() {
                    var quantity = parseInt($(this).val()) || 0;
                    var price = parseFloat($(this).attr('data-price'));
                    var offerPrice = parseFloat($(this).attr('data-offer-price'));
                    var productId = $(this).attr('data-product-id');
                    
                    if (quantity > 0) {
                        var rowTotal = quantity * offerPrice;
                        var rowDiscount = quantity * (price - offerPrice);
                        
                        subtotal += rowTotal;
                        totalProductDiscount += rowDiscount;
                        
                        productsData.push({
                            product_id: productId,
                            quantity: quantity,
                            unit_price: price,
                            offer_price: offerPrice,
                            total_price: rowTotal,
                            discount_percentage: rowDiscount > 0 ? ((price - offerPrice) / price * 100).toFixed(2) : 0,
                            discount_value: rowDiscount
                        });
                    }
                });
                
                // Calculate additional discount
                var additionalDiscount = calculateAdditionalDiscount(subtotal);
                
                var deliveryFee = parseFloat($('#total-delivery-fee').text()) || 0;
                var netTotal = subtotal - additionalDiscount; // Net total after all discounts, before delivery
                var finalTotal = netTotal + deliveryFee; // Final total including delivery
                var totalDiscounts = totalProductDiscount + additionalDiscount;
                
                // Update display
                $('#subtotal').text(subtotal.toFixed(2));
                $('#total-product-discount').text(totalProductDiscount.toFixed(2));
                $('#additional-discount-amount').text(additionalDiscount.toFixed(2));
                $('#final-total').text(finalTotal.toFixed(2));
                
                // Update hidden fields - these values go to the database
                $('#hidden_total_prices').val(netTotal); // Net price after all discounts, before delivery
                $('#hidden_total_discounts').val(totalDiscounts); // Total of all discounts (product + additional)
                $('#hidden_products_data').val(JSON.stringify(productsData));
            }
        });
    </script>
@endsection