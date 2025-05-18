

<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .product-card {
        height: 100%;
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .selected-product-item {
        border-left: 4px solid #007bff;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><?php echo e(__('messages.Product Selection')); ?></h3>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date"><?php echo e(__('messages.Select Date')); ?></label>
                                <input type="date" id="date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="time"><?php echo e(__('messages.Select Time')); ?></label>
                                <input type="time" id="time" class="form-control" value="<?php echo e(date('H:i')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user_id"><?php echo e(__('messages.Customer')); ?></label>
                                <select class="form-control select2" id="user_id" name="user_id" required>
                                    <option value=""><?php echo e(__('messages.Select Customer')); ?></option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" data-phone="<?php echo e($user->phone); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="showAll"><?php echo e(__('messages.Show Products')); ?></label>
                                <select id="showAll" class="form-control">
                                    <option value="false"><?php echo e(__('messages.Available Only')); ?></option>
                                    <option value="true"><?php echo e(__('messages.All Products')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button id="filterBtn" class="btn btn-primary">
                                <i class="fas fa-filter"></i> <?php echo e(__('messages.Filter Products')); ?>

                            </button>
                        </div>
                    </div>
                    
                    <div id="productsContainer" class="row">
                        <!-- Products will be loaded here -->
                        <div class="col-12 text-center">
                            <p><?php echo e(__('messages.Select date and options to load products')); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4><?php echo e(__('messages.Selected Products')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div id="selectedProducts" class="list-group">
                                        <!-- Selected products will appear here -->
                                        <div class="text-center">
                                            <p><?php echo e(__('messages.No products selected')); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button id="sendWhatsAppBtn" class="btn btn-success" disabled>
                                        <i class="fab fa-whatsapp"></i> <?php echo e(__('messages.Send via WhatsApp')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();
        
        let selectedProducts = [];
        let userPhone = '';
        
        // Filter button click handler
        $('#filterBtn').click(function() {
            loadProducts();
        });
        
        // User selection change handler
        $('#user_id').change(function() {
            userPhone = $(this).find(':selected').data('phone') || '';
            updateWhatsAppButton();
        });
        
        // Load products function
        function loadProducts() {
            const date = $('#date').val();
            const showAll = $('#showAll').val();
            
            if (!date) {
                alert('<?php echo e(__("messages.Please select a date")); ?>');
                return;
            }
            
            $.ajax({
                url: '<?php echo e(route("products.filtered")); ?>',
                type: 'POST',
                data: {
                    date: date,
                    show_all: showAll,
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                beforeSend: function() {
                    $('#productsContainer').html('<div class="col-12 text-center"><p><?php echo e(__("messages.Loading products...")); ?></p></div>');
                },
                success: function(response) {
                    renderProducts(response.products);
                },
                error: function() {
                    $('#productsContainer').html('<div class="col-12 text-center"><p><?php echo e(__("messages.Error loading products. Please try again.")); ?></p></div>');
                }
            });
        }
        
        // Render products function
        function renderProducts(products) {
            if (products.length === 0) {
                $('#productsContainer').html('<div class="col-12 text-center"><p><?php echo e(__("messages.No products found")); ?></p></div>');
                return;
            }
            
            let html = '';
            
            products.forEach(product => {
                const isSelected = selectedProducts.some(p => p.id === product.id);
                const availabilityClass = product.available ? 'bg-white' : 'bg-light text-muted';
                const price = product.offer_price ? 
                    `<del>${product.selling_price}</del> <span class="text-danger">${product.offer_price}</span>` : 
                    product.selling_price;
                
                html += `
                <div class="col-md-4 mb-4">
                    <div class="card product-card ${availabilityClass}" data-id="${product.id}">
                        <div class="position-absolute" style="right: 10px; top: 10px;">
                            ${product.available ? 
                                '<span class="badge badge-success"><?php echo e(__("messages.Available")); ?></span>' : 
                                '<span class="badge badge-warning"><?php echo e(__("messages.Unavailable")); ?></span>'}
                        </div>
                        <img src="${product.image}" class="card-img-top" alt="${product.name_en}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${product.name_en} / ${product.name_ar}</h5>
                            <p class="card-text">${product.description_en.substring(0, 100)}...</p>
                            <p class="card-text"><?php echo e(__("messages.Price")); ?>: ${price}</p>
                            <button class="btn ${isSelected ? 'btn-danger remove-product' : 'btn-primary add-product'}" 
                                data-id="${product.id}" 
                                data-name="${product.name_en}" 
                                data-price="${product.offer_price || product.selling_price}"
                                data-image="${product.image}"
                                ${!product.available ? 'disabled' : ''}>
                                ${isSelected ? '<?php echo e(__("messages.Remove")); ?>' : '<?php echo e(__("messages.Select")); ?>'}
                            </button>
                        </div>
                    </div>
                </div>
                `;
            });
            
            $('#productsContainer').html(html);
            attachProductEventListeners();
        }
        
        // Attach event listeners to product cards
        function attachProductEventListeners() {
            $('.add-product').click(function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = $(this).data('price');
                const productImage = $(this).data('image');
                
                // Add to selected products if not already there
                if (!selectedProducts.some(p => p.id === productId)) {
                    selectedProducts.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage
                    });
                    
                    // Update UI
                    $(this).removeClass('btn-primary add-product').addClass('btn-danger remove-product').text('<?php echo e(__("messages.Remove")); ?>');
                    updateSelectedProductsList();
                    updateWhatsAppButton();
                }
            });
            
            $('.remove-product').click(function() {
                const productId = $(this).data('id');
                
                // Remove from selected products
                selectedProducts = selectedProducts.filter(p => p.id !== productId);
                
                // Update UI
                $(this).removeClass('btn-danger remove-product').addClass('btn-primary add-product').text('<?php echo e(__("messages.Select")); ?>');
                updateSelectedProductsList();
                updateWhatsAppButton();
            });
        }
        
        // Update selected products list
        function updateSelectedProductsList() {
            if (selectedProducts.length === 0) {
                $('#selectedProducts').html('<div class="text-center"><p><?php echo e(__("messages.No products selected")); ?></p></div>');
                return;
            }
            
            let html = '';
            
            selectedProducts.forEach(product => {
                html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <img src="${product.image}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover;" class="mr-3">
                        <span>${product.name}</span>
                    </div>
                    <div>
                        <span class="badge badge-primary badge-pill">${product.price}</span>
                        <button class="btn btn-sm btn-danger remove-selected" data-id="${product.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                `;
            });
            
            $('#selectedProducts').html(html);
            
            // Attach remove event to selected products
            $('.remove-selected').click(function() {
                const productId = $(this).data('id');
                
                // Remove from selected products
                selectedProducts = selectedProducts.filter(p => p.id !== productId);
                
                // Update the main product list button if it exists
                $(`.product-card[data-id="${productId}"] button`).removeClass('btn-danger remove-product').addClass('btn-primary add-product').text('<?php echo e(__("messages.Select")); ?>');
                
                updateSelectedProductsList();
                updateWhatsAppButton();
            });
        }
        
        // Update WhatsApp button state
        function updateWhatsAppButton() {
            const btn = $('#sendWhatsAppBtn');
            
            if (selectedProducts.length > 0 && userPhone) {
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
            }
        }
        
        // WhatsApp button click handler
        $('#sendWhatsAppBtn').click(function() {
            if (selectedProducts.length === 0 || !userPhone) {
                alert('<?php echo e(__("messages.Please select products and a user with a valid phone number")); ?>');
                return;
            }
            
            const date = $('#date').val();
            const time = $('#time').val();
            
            // Format WhatsApp message
            let message = `*<?php echo e(__("messages.Hello! Here are the selected products for")); ?> ${date} <?php echo e(__("messages.at")); ?> ${time}:*\n\n`;
            
            selectedProducts.forEach((product, index) => {
                // Get absolute image URL
                const imageUrl = product.image;
                
                message += `*${index+1}. ${product.name}*\n`;
                message += `<?php echo e(__("messages.Price")); ?>: ${product.price}\n`;
                message += `<?php echo e(__("messages.Image")); ?>: ${imageUrl}\n\n`;
            });
            
            message += `\n*<?php echo e(__("messages.Total Products")); ?>: ${selectedProducts.length}*`;
            
            // Format phone number (remove + if present)
            const phoneNumber = userPhone.replace(/^\+/, '');
            
            // Create WhatsApp URL
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
            
            // Open in new tab
            window.open(whatsappUrl, '_blank');
        });
        
        // Initialize - trigger filter on page load if user already selected
        if ($('#user_id').val()) {
            userPhone = $('#user_id').find(':selected').data('phone') || '';
            updateWhatsAppButton();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\game\resources\views/admin/products/selection.blade.php ENDPATH**/ ?>