

<?php $__env->startSection('content'); ?>
<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="checkout-left">
            <h2 class="checkout-title"><?php echo e(__('messages.Complete Registration Payment')); ?></h2>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            
            <form action="<?php echo e(route('checkout.process')); ?>" method="POST" id="checkoutForm">
                <?php echo csrf_field(); ?>
                <div class="checkout-block">
                    <h3 class="checkout-subtitle"><?php echo e(__('messages.Personal Details')); ?></h3>
                    <a href="<?php echo e(route('address.create', ['from_checkout' => 1])); ?>" class="add-address-btn">
                        <i class="fa fa-plus"></i> <?php echo e(__('messages.Add New Address')); ?>

                    </a>
                       <br>
                      
                    <div class="address-dropdown" id="addressDropdown">
                        <div class="selected-address" id="selectedAddress">
                            <?php echo e(__('messages.Choose Your Address')); ?>

                            <span class="arrow-down">&#9662;</span>
                        </div>
                        <div class="address-options" id="addressOptions">
                            <?php $__empty_1 = true; $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="address-option" 
                                     data-address-id="<?php echo e($address->id); ?>" 
                                     data-delivery-fee="<?php echo e($address->delivery->price); ?>">
                                    <?php echo e($address->getFormattedAddress()); ?>

                                    <?php if($address->is_default): ?>
                                        <span class="default-badge"><?php echo e(__('messages.Default')); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="no-address"><?php echo e(__('messages.No addresses found')); ?></div>
                            <?php endif; ?>
                            <div class="add-new-address" id="addNewAddressBtn">+ <?php echo e(__('messages.Add New Address')); ?></div>
                        </div>
                    </div>
                    <input type="hidden" name="address_id" id="addressId" value="<?php echo e($addresses->where('default', 1)->first()->id ?? ''); ?>">
                    <?php $__errorArgs = ['address_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
               
                <h4 class="checkout-subsubtitle"><?php echo e(__('messages.Choose the branch you would like to get your order from')); ?></h4>
                <select name="branch_id" class="checkout-select">
                    <option value=""><?php echo e(__('messages.Choose the branch')); ?></option>
                    <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>" <?php echo e(old('branch_id') == $branch->id ? 'selected' : ''); ?>>
                            <?php echo e($branch->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <div class="delivery-methods">
                    <button type="button" class="delivery-btn active" data-delivery="delivery"><?php echo e(__('messages.Delivery')); ?></button>
                    <button type="button" class="delivery-btn" data-delivery="pickup"><?php echo e(__('messages.Pick-up')); ?></button>
                </div>
                <input type="hidden" name="delivery_method" id="deliveryMethod" value="delivery">
                <?php $__errorArgs = ['delivery_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="checkout-block">
                    <h3 class="checkout-subtitle"><?php echo e(__('messages.Payment Methods')); ?></h3>
                    <div class="payment-methods">
                        <button type="button" class="payment-btn active" data-payment="visa">VISA</button>
                        <button type="button" class="payment-btn" data-payment="cash"><?php echo e(__('messages.Cash')); ?></button>
                    </div>
                    <input type="hidden" name="payment_method" id="paymentMethod" value="visa">
                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="checkout-block" id="cardDetailsBlock">
                    <h3 class="checkout-subtitle"><?php echo e(__('messages.Card Details')); ?></h3>
                    <input type="text" name="cardholder_name" placeholder="<?php echo e(__('messages.Cardholder\'s Name')); ?>" class="checkout-input" value="<?php echo e(old('cardholder_name')); ?>">
                    <?php $__errorArgs = ['cardholder_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    
                    <input type="text" name="card_number" placeholder="<?php echo e(__('messages.Card Number')); ?>" class="checkout-input" value="<?php echo e(old('card_number')); ?>">
                    <?php $__errorArgs = ['card_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="error-message"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    
                    <div class="checkout-input-group">
                        <input type="text" name="expiry_date" placeholder="<?php echo e(__('messages.Expiry')); ?>" class="checkout-input small" value="<?php echo e(old('expiry_date')); ?>">
                        <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        
                        <input type="text" name="cvc" placeholder="CVC" class="checkout-input small" value="<?php echo e(old('cvc')); ?>">
                        <?php $__errorArgs = ['cvc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                </div>
                
                <button type="submit" class="payment-now-btn"><?php echo e(__('messages.Payment Now')); ?></button>
            </form>
        </div>
        
        <div class="checkout-right">
            <h2 class="cart-totals-title"><?php echo e(__('messages.Cart Totals')); ?></h2>
            <table class="cart-totals-table">
                <tr>
                    <td><?php echo e(__('messages.Subtotal')); ?></td>
                    <td><?php echo e($subtotal); ?> JD</td>
                </tr>
                <?php if($totalDiscount > 0): ?>
                    <tr>
                        <td><?php echo e(__('messages.Discount')); ?></td>
                        <td><?php echo e($totalDiscount); ?> JD</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td><?php echo e(__('messages.Delivering')); ?></td>
                    <td><?php echo e(__('messages.Free')); ?></td>
                </tr>
                <tr>
                    <td><?php echo e(__('messages.Total')); ?></td>
                    <td><strong><?php echo e($total); ?> JD</strong></td>
                </tr>
            </table>
        </div>
        
    </div>
</section>
<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address dropdown
        const addressDropdown = document.getElementById('addressDropdown');
        const selectedAddress = document.getElementById('selectedAddress');
        const addressOptions = document.getElementById('addressOptions');
        const addressId = document.getElementById('addressId');
        const addressOptionEls = document.querySelectorAll('.address-option');
        
        // Delivery fee elements
        const deliveryFeeElement = document.querySelector('.cart-totals-table tr:nth-child(3) td:last-child');
        const totalElement = document.querySelector('.cart-totals-table tr:last-child td:last-child strong');
        const subtotal = <?php echo e($subtotal - ($totalDiscount ?? 0)); ?>; // Subtotal minus any discount
        
        // Function to update total based on delivery fee
        function updateTotal(deliveryFee) {
            const newTotal = subtotal + parseFloat(deliveryFee);
            totalElement.textContent = newTotal.toFixed(2) + ' JD';
        }
        
        // Set initial selected address if available
        if (addressOptionEls.length > 0) {
            const defaultAddress = document.querySelector('.address-option .default-badge');
            if (defaultAddress) {
                const addressOption = defaultAddress.closest('.address-option');
                selectedAddress.innerHTML = addressOption.textContent;
                addressId.value = addressOption.dataset.addressId;
                
                // Set initial delivery fee
                if (addressOption.dataset.deliveryFee) {
                    deliveryFeeElement.textContent = addressOption.dataset.deliveryFee + ' JD';
                    updateTotal(addressOption.dataset.deliveryFee);
                }
            } else {
                selectedAddress.innerHTML = addressOptionEls[0].textContent;
                addressId.value = addressOptionEls[0].dataset.addressId;
                
                // Set initial delivery fee
                if (addressOptionEls[0].dataset.deliveryFee) {
                    deliveryFeeElement.textContent = addressOptionEls[0].dataset.deliveryFee + ' JD';
                    updateTotal(addressOptionEls[0].dataset.deliveryFee);
                }
            }
        }
        
        // Toggle address dropdown
        selectedAddress.addEventListener('click', function() {
            addressOptions.classList.toggle('show');
        });
        
        // Select address
        addressOptionEls.forEach(option => {
            option.addEventListener('click', function() {
                selectedAddress.innerHTML = this.textContent;
                addressId.value = this.dataset.addressId;
                addressOptions.classList.remove('show');
                
                // Update delivery fee and total when address changes
                if (this.dataset.deliveryFee) {
                    deliveryFeeElement.textContent = this.dataset.deliveryFee + ' JD';
                    updateTotal(this.dataset.deliveryFee);
                }
            });
        });
        
        // Add new address button
        document.getElementById('addNewAddressBtn').addEventListener('click', function() {
            window.location.href = '<?php echo e(route("address.create")); ?>?from_checkout=1';
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!addressDropdown.contains(event.target)) {
                addressOptions.classList.remove('show');
            }
        });
        
        // Payment methods
        const paymentBtns = document.querySelectorAll('.payment-btn');
        const paymentMethod = document.getElementById('paymentMethod');
        const cardDetailsBlock = document.getElementById('cardDetailsBlock');
        
        paymentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                paymentBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update hidden input
                paymentMethod.value = this.dataset.payment;
                
                // Toggle card details visibility
                if (this.dataset.payment === 'visa') {
                    cardDetailsBlock.style.display = 'block';
                } else {
                    cardDetailsBlock.style.display = 'none';
                }
            });
        });
        
        // Delivery methods
        const deliveryBtns = document.querySelectorAll('.delivery-btn');
        const deliveryMethod = document.getElementById('deliveryMethod');
        const deliveryRow = document.querySelector('.cart-totals-table tr:nth-child(3)');
        
        deliveryBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                deliveryBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update hidden input
                deliveryMethod.value = this.dataset.delivery;
                
                // Update delivery fee based on method
                if (this.dataset.delivery === 'pickup') {
                    // Free for pickup
                    deliveryFeeElement.textContent = '<?php echo e(__("messages.Free")); ?>';
                    updateTotal(0);
                } else {
                    // For delivery, use the selected address's delivery fee
                    const selectedAddressId = addressId.value;
                    const selectedOption = document.querySelector(`.address-option[data-address-id="${selectedAddressId}"]`);
                    
                    if (selectedOption && selectedOption.dataset.deliveryFee) {
                        deliveryFeeElement.textContent = selectedOption.dataset.deliveryFee + ' JD';
                        updateTotal(selectedOption.dataset.deliveryFee);
                    }
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dynamite\resources\views/user/checkout.blade.php ENDPATH**/ ?>