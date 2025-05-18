@extends('layouts.user')

@section('content')
<section class="checkout-section">
    <div class="checkout-container">
        
        <div class="checkout-left">
            <h2 class="checkout-title">{{ __('messages.Complete Registration Payment') }}</h2>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="checkout-block">
                    <h3 class="checkout-subtitle">{{ __('messages.Personal Details') }}</h3>
                    <a href="{{ route('address.create', ['from_checkout' => 1]) }}" class="add-address-btn">
                        <i class="fa fa-plus"></i> {{ __('messages.Add New Address') }}
                    </a>
                       <br>
                      
                    <div class="address-dropdown" id="addressDropdown">
                        <div class="selected-address" id="selectedAddress">
                            {{ __('messages.Choose Your Address') }}
                            <span class="arrow-down">&#9662;</span>
                        </div>
                        <div class="address-options" id="addressOptions">
                            @forelse($addresses as $address)
                                <div class="address-option" 
                                     data-address-id="{{ $address->id }}" 
                                     data-delivery-fee="{{ $address->delivery->price }}">
                                    {{ $address->getFormattedAddress() }}
                                    @if($address->is_default)
                                        <span class="default-badge">{{ __('messages.Default') }}</span>
                                    @endif
                                </div>
                            @empty
                                <div class="no-address">{{ __('messages.No addresses found') }}</div>
                            @endforelse
                            <div class="add-new-address" id="addNewAddressBtn">+ {{ __('messages.Add New Address') }}</div>
                        </div>
                    </div>
                    <input type="hidden" name="address_id" id="addressId" value="{{ $addresses->where('default', 1)->first()->id ?? '' }}">
                    @error('address_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
               
                <h4 class="checkout-subsubtitle">{{ __('messages.Choose the branch you would like to get your order from') }}</h4>
                <select name="branch_id" class="checkout-select">
                    <option value="">{{ __('messages.Choose the branch') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                
                <div class="delivery-methods">
                    <button type="button" class="delivery-btn active" data-delivery="delivery">{{ __('messages.Delivery') }}</button>
                    <button type="button" class="delivery-btn" data-delivery="pickup">{{ __('messages.Pick-up') }}</button>
                </div>
                <input type="hidden" name="delivery_method" id="deliveryMethod" value="delivery">
                @error('delivery_method')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="checkout-block">
                    <h3 class="checkout-subtitle">{{ __('messages.Payment Methods') }}</h3>
                    <div class="payment-methods">
                        <button type="button" class="payment-btn active" data-payment="visa">VISA</button>
                        <button type="button" class="payment-btn" data-payment="cash">{{ __('messages.Cash') }}</button>
                    </div>
                    <input type="hidden" name="payment_method" id="paymentMethod" value="visa">
                    @error('payment_method')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="checkout-block" id="cardDetailsBlock">
                    <h3 class="checkout-subtitle">{{ __('messages.Card Details') }}</h3>
                    <input type="text" name="cardholder_name" placeholder="{{ __('messages.Cardholder\'s Name') }}" class="checkout-input" value="{{ old('cardholder_name') }}">
                    @error('cardholder_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    
                    <input type="text" name="card_number" placeholder="{{ __('messages.Card Number') }}" class="checkout-input" value="{{ old('card_number') }}">
                    @error('card_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    
                    <div class="checkout-input-group">
                        <input type="text" name="expiry_date" placeholder="{{ __('messages.Expiry') }}" class="checkout-input small" value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        
                        <input type="text" name="cvc" placeholder="CVC" class="checkout-input small" value="{{ old('cvc') }}">
                        @error('cvc')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>
                
                <button type="submit" class="payment-now-btn">{{ __('messages.Payment Now') }}</button>
            </form>
        </div>
        
        <div class="checkout-right">
            <h2 class="cart-totals-title">{{ __('messages.Cart Totals') }}</h2>
            <table class="cart-totals-table">
                <tr>
                    <td>{{ __('messages.Subtotal') }}</td>
                    <td>{{ $subtotal }} JD</td>
                </tr>
                @if($totalDiscount > 0)
                    <tr>
                        <td>{{ __('messages.Discount') }}</td>
                        <td>{{ $totalDiscount }} JD</td>
                    </tr>
                @endif
                <tr>
                    <td>{{ __('messages.Delivering') }}</td>
                    <td>{{ __('messages.Free') }}</td>
                </tr>
                <tr>
                    <td>{{ __('messages.Total') }}</td>
                    <td><strong>{{ $total }} JD</strong></td>
                </tr>
            </table>
        </div>
        
    </div>
</section>
@push('scripts')
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
        const subtotal = {{ $subtotal - ($totalDiscount ?? 0) }}; // Subtotal minus any discount
        
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
            window.location.href = '{{ route("address.create") }}?from_checkout=1';
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
                    deliveryFeeElement.textContent = '{{ __("messages.Free") }}';
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
@endpush
@endsection