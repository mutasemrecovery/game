<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.orders')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Selected item text inside multiple select2 */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #000 !important; /* black text */
    background-color: #e9ecef !important; /* light background for contrast (optional) */
    border: 1px solid #ced4da !important;  /* optional for better border visibility */
}

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.orders')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="<?php echo e(route('orders.store')); ?>" method="post" enctype='multipart/form-data'>
                <?php echo csrf_field(); ?>
                <div class="row">
                    <!-- Order Date and Time -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_date"><?php echo e(__('messages.Order Date')); ?></label>
                            <input type="datetime-local" class="form-control" id="order_date" name="date" 
                                value="<?php echo e(old('date')); ?>" required>
                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Customer Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id"><?php echo e(__('messages.Customer')); ?></label>
                            <select class="form-control select2 " id="user_id" name="user_id" required>
                                <option value=""><?php echo e(__('messages.Select Customer')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Products Selection -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="products"><?php echo e(__('messages.Products')); ?></label>
                            <select class="form-control select2" id="products" name="products[]" multiple required>
                                <!-- Products will be loaded via AJAX -->
                            </select>
                            <?php $__errorArgs = ['products'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="delivery_id"><?php echo e(__('messages.Delivery')); ?></label>
                            <select class="form-control select2" id="delivery_id" name="delivery_id">
                                <option value=""><?php echo e(__('messages.Select Delivery')); ?></option>
                                <?php $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($delivery->id); ?>" <?php echo e(old('delivery_id') == $delivery->id ? 'selected' : ''); ?>>
                                        <?php echo e($delivery->place); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['delivery_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                  

                    <!-- Payment Type -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_type"><?php echo e(__('messages.Payment Type')); ?></label>
                            <select class="form-control" id="payment_type" name="payment_type" required>
                                <option value=""><?php echo e(__('messages.Select Payment Type')); ?></option>
                                <option value="cash" <?php echo e(old('payment_type') == 'cash' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.Cash')); ?>

                                </option>
                                <option value="card" <?php echo e(old('payment_type') == 'card' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.Card')); ?>

                                </option>
                                <option value="transfer" <?php echo e(old('payment_type') == 'transfer' ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.Bank Transfer')); ?>

                                </option>
                            </select>
                            <?php $__errorArgs = ['payment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_status"><?php echo e(__('messages.Payment Status')); ?></label>
                            <select class="form-control" id="payment_status" name="payment_status" required>
                                <option value="2" <?php echo e(old('payment_status', 2) == 2 ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.Unpaid')); ?>

                                </option>
                                <option value="1" <?php echo e(old('payment_status') == 1 ? 'selected' : ''); ?>>
                                    <?php echo e(__('messages.Paid')); ?>

                                </option>
                            </select>
                            <?php $__errorArgs = ['payment_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Selected Products Summary -->
                    <div class="col-md-12">
                        <div id="selected-products-summary" style="display: none;">
                            <h4><?php echo e(__('messages.Selected Products')); ?></h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('messages.Product')); ?></th>
                                        <th><?php echo e(__('messages.Quantity')); ?></th>
                                        <th><?php echo e(__('messages.Unit Price')); ?></th>
                                        <th><?php echo e(__('messages.Discount')); ?></th>
                                        <th><?php echo e(__('messages.Total')); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="selected-products-table">
                                    <!-- Dynamic content -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"><?php echo e(__('messages.Delivery Fee')); ?></th>
                                        <th id="total-delivery-fee">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"><?php echo e(__('messages.Total Discount')); ?></th>
                                        <th id="total-discount">0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4"><?php echo e(__('messages.Final Total')); ?></th>
                                        <th id="final-total">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Hidden fields for calculated values -->
                    <input type="hidden" name="total_prices" id="hidden_total_prices">
                    <input type="hidden" name="total_discounts" id="hidden_total_discounts">
                    <input type="hidden" name="products_data" id="hidden_products_data">

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit"
                                class="btn btn-primary btn-sm"><?php echo e(__('messages.Submit')); ?></button>
                            <a href="<?php echo e(route('orders.index')); ?>"
                                class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
            });

            // When order date changes, fetch available products
            $('#order_date').on('change', function() {
                var orderDate = $(this).val();
                if (orderDate) {
                    fetchAvailableProducts(orderDate);
                }
            });

            // When products are selected, update the summary
            $('#products').on('change', function() {
                updateProductsSummary();
            });

            // When delivery fee changes, update total
            $('#delivery_fee').on('input', function() {
                updateTotals();
            });

            function fetchAvailableProducts(orderDate) {
                $.ajax({
                    url: '<?php echo e(route("orders.available-products")); ?>',
                    method: 'GET',
                    data: { date: orderDate },
                    success: function(response) {
                        var productsSelect = $('#products');
                        productsSelect.empty();
                        
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
                        
                        productsSelect.trigger('change');
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
                        var price = parseFloat($(product.element).attr('data-price'));
                        var offerPrice = parseFloat($(product.element).attr('data-offer-price'));
                        var discount = price - offerPrice;
                        var discountPercentage = discount > 0 ? ((discount / price) * 100).toFixed(2) : 0;
                        
                        var row = `
                            <tr>
                                <td>${product.text}</td>
                                <td>
                                    <input type="number" class="form-control quantity" min="1" value="1" 
                                        data-product-id="${product.id}" 
                                        data-price="${price}" 
                                        data-offer-price="${offerPrice}">
                                </td>
                                <td>${price.toFixed(2)}</td>
                                <td>${discountPercentage}%</td>
                                <td class="total-cell">${offerPrice.toFixed(2)}</td>
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

            function updateTotals() {
                var totalPrice = 0;
                var totalDiscount = 0;
                var productsData = [];
                
                $('.quantity').each(function() {
                    var quantity = parseInt($(this).val()) || 0;
                    var price = parseFloat($(this).attr('data-price'));
                    var offerPrice = parseFloat($(this).attr('data-offer-price'));
                    var productId = $(this).attr('data-product-id');
                    
                    if (quantity > 0) {
                        var rowTotal = quantity * offerPrice;
                        var rowDiscount = quantity * (price - offerPrice);
                        
                        totalPrice += rowTotal;
                        totalDiscount += rowDiscount;
                        
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
                
                var deliveryFee = parseFloat($('#delivery_fee').val()) || 0;
                var finalTotal = totalPrice + deliveryFee;
                
                $('#total-delivery-fee').text(deliveryFee.toFixed(2));
                $('#total-discount').text(totalDiscount.toFixed(2));
                $('#final-total').text(finalTotal.toFixed(2));
                
                // Update hidden fields
                $('#hidden_total_prices').val(totalPrice);
                $('#hidden_total_discounts').val(totalDiscount);
                $('#hidden_products_data').val(JSON.stringify(productsData));
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\game\resources\views/admin/orders/create.blade.php ENDPATH**/ ?>