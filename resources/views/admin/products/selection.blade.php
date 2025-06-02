@extends('layouts.admin')

@section('css')
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
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('messages.Product Selection') }}</h3>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date">{{ __('messages.Select Date') }}</label>
                                <input type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="time">{{ __('messages.Select Time') }}</label>
                                <input type="time" id="time" class="form-control" value="{{ date('H:i') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="user_id">{{ __('messages.Customer') }}</label>
                                <select class="form-control select2" id="user_id" name="user_id" required>
                                    <option value="">{{ __('messages.Select Customer') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-phone="{{ $user->phone }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="showAll">{{ __('messages.Show Products') }}</label>
                                <select id="showAll" class="form-control">
                                    <option value="false">{{ __('messages.Available Only') }}</option>
                                    <option value="true">{{ __('messages.All Products') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button id="filterBtn" class="btn btn-primary">
                                <i class="fas fa-filter"></i> {{ __('messages.Filter Products') }}
                            </button>
                            <button id="selectAllBtn" class="btn btn-secondary ml-2" style="display: none;">
                                <i class="fas fa-check-double"></i> {{ __('messages.Select All Available') }}
                            </button>
                            <button id="deselectAllBtn" class="btn btn-warning ml-2" style="display: none;">
                                <i class="fas fa-times-circle"></i> {{ __('messages.Deselect All') }}
                            </button>
                        </div>
                    </div>
                    
                    <div id="productsContainer" class="row">
                        <!-- Products will be loaded here -->
                        <div class="col-12 text-center">
                            <p>{{ __('messages.Select date and options to load products') }}</p>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('messages.Selected Products') }}</h4>
                                    <small id="selectedCount" class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    <div id="selectedProducts" class="list-group">
                                        <!-- Selected products will appear here -->
                                        <div class="text-center">
                                            <p>{{ __('messages.No products selected') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button id="sendWhatsAppBtn" class="btn btn-success" disabled>
                                        <i class="fab fa-whatsapp"></i> {{ __('messages.Send via WhatsApp') }}
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
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();
        
        let selectedProducts = [];
        let availableProducts = []; // Store available products for select all functionality
        let userPhone = '';
        
        // Filter button click handler
        $('#filterBtn').click(function() {
            loadProducts();
        });
        
        // Select All button click handler
        $('#selectAllBtn').click(function() {
            // Select all available products
            availableProducts.forEach(product => {
                if (product.available && !selectedProducts.some(p => p.id === product.id)) {
                    selectedProducts.push({
                        id: product.id,
                        name: product.name_en,
                        price: product.offer_price || product.selling_price,
                        image: product.image
                    });
                }
            });
            
            // Update UI for all product cards
            $('.add-product').each(function() {
                const productId = $(this).data('id');
                const product = availableProducts.find(p => p.id === productId);
                
                if (product && product.available) {
                    $(this).removeClass('btn-primary add-product')
                           .addClass('btn-danger remove-product')
                           .text('{{ __("messages.Remove") }}');
                }
            });
            
            updateSelectedProductsList();
            updateWhatsAppButton();
            updateSelectAllButtons();
        });
        
        // Deselect All button click handler
        $('#deselectAllBtn').click(function() {
            selectedProducts = [];
            
            // Update UI for all product cards
            $('.remove-product').removeClass('btn-danger remove-product')
                               .addClass('btn-primary add-product')
                               .text('{{ __("messages.Select") }}');
            
            updateSelectedProductsList();
            updateWhatsAppButton();
            updateSelectAllButtons();
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
                alert('{{ __("messages.Please select a date") }}');
                return;
            }
            
            $.ajax({
                url: '{{ route("products.filtered") }}',
                type: 'POST',
                data: {
                    date: date,
                    show_all: showAll,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#productsContainer').html('<div class="col-12 text-center"><p>{{ __("messages.Loading products...") }}</p></div>');
                    $('#selectAllBtn, #deselectAllBtn').hide();
                },
                success: function(response) {
                    availableProducts = response.products; // Store products for select all functionality
                    renderProducts(response.products);
                    updateSelectAllButtons();
                },
                error: function() {
                    $('#productsContainer').html('<div class="col-12 text-center"><p>{{ __("messages.Error loading products. Please try again.") }}</p></div>');
                    $('#selectAllBtn, #deselectAllBtn').hide();
                }
            });
        }
        
        // Update Select All buttons visibility and state
        function updateSelectAllButtons() {
            if (availableProducts.length > 0) {
                const availableCount = availableProducts.filter(p => p.available).length;
                const selectedAvailableCount = selectedProducts.filter(p => {
                    const product = availableProducts.find(ap => ap.id === p.id);
                    return product && product.available;
                }).length;
                
                if (availableCount > 0) {
                    $('#selectAllBtn').show();
                    
                    if (selectedAvailableCount === availableCount) {
                        $('#selectAllBtn').hide();
                        $('#deselectAllBtn').show();
                    } else if (selectedProducts.length > 0) {
                        $('#deselectAllBtn').show();
                    } else {
                        $('#deselectAllBtn').hide();
                    }
                }
            } else {
                $('#selectAllBtn, #deselectAllBtn').hide();
            }
        }
        
        // Render products function
        function renderProducts(products) {
            if (products.length === 0) {
                $('#productsContainer').html('<div class="col-12 text-center"><p>{{ __("messages.No products found") }}</p></div>');
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
                                '<span class="badge badge-success">{{ __("messages.Available") }}</span>' : 
                                '<span class="badge badge-warning">{{ __("messages.Unavailable") }}</span>'}
                        </div>
                        <img src="${product.image}" class="card-img-top" alt="${product.name_en}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">${product.name_en} / ${product.name_ar}</h5>
                            <p class="card-text">${product.description_en.substring(0, 100)}...</p>
                            <p class="card-text">{{ __("messages.Price") }}: ${price}</p>
                            <button class="btn ${isSelected ? 'btn-danger remove-product' : 'btn-primary add-product'}" 
                                data-id="${product.id}" 
                                data-name="${product.name_en}" 
                                data-price="${product.offer_price || product.selling_price}"
                                data-image="${product.image}"
                                ${!product.available ? 'disabled' : ''}>
                                ${isSelected ? '{{ __("messages.Remove") }}' : '{{ __("messages.Select") }}'}
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
                    $(this).removeClass('btn-primary add-product').addClass('btn-danger remove-product').text('{{ __("messages.Remove") }}');
                    updateSelectedProductsList();
                    updateWhatsAppButton();
                    updateSelectAllButtons();
                }
            });
            
            $('.remove-product').click(function() {
                const productId = $(this).data('id');
                
                // Remove from selected products
                selectedProducts = selectedProducts.filter(p => p.id !== productId);
                
                // Update UI
                $(this).removeClass('btn-danger remove-product').addClass('btn-primary add-product').text('{{ __("messages.Select") }}');
                updateSelectedProductsList();
                updateWhatsAppButton();
                updateSelectAllButtons();
            });
        }
        
        // Update selected products list
        function updateSelectedProductsList() {
            if (selectedProducts.length === 0) {
                $('#selectedProducts').html('<div class="text-center"><p>{{ __("messages.No products selected") }}</p></div>');
                $('#selectedCount').text('');
                return;
            }
            
            $('#selectedCount').text(`(${selectedProducts.length} {{ __("messages.products selected") }})`);
            
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
                $(`.product-card[data-id="${productId}"] button`).removeClass('btn-danger remove-product').addClass('btn-primary add-product').text('{{ __("messages.Select") }}');
                
                updateSelectedProductsList();
                updateWhatsAppButton();
                updateSelectAllButtons();
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
                alert('{{ __("messages.Please select products and a user with a valid phone number") }}');
                return;
            }
            
            const date = $('#date').val();
            const time = $('#time').val();
            
            // Format WhatsApp message
            let message = `*{{ __("messages.Hello! Here are the selected products for") }} ${date} {{ __("messages.at") }} ${time}:*\n\n`;
            
            selectedProducts.forEach((product, index) => {
                // Get absolute image URL
                const imageUrl = product.image;
                
                message += `*${index+1}. ${product.name}*\n`;
                message += `{{ __("messages.Price") }}: ${product.price}\n`;
                message += `{{ __("messages.Image") }}: ${imageUrl}\n\n`;
            });
            
            message += `\n*{{ __("messages.Total Products") }}: ${selectedProducts.length}*`;
            
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
@endsection