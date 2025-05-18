@extends('layouts.user')

@section('content')
<section class="product-details-section">
    <div class="product-details-container">
      <div class="product-image-gallery">
        @if($product->productImages->count() > 0)
          <img src="{{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}" alt="{{ $locale == 'en' ? $product->name_en : $product->name_ar }}" class="main-product-image" id="mainProductImage">
        
          <div class="product-thumbnails">
            @foreach($product->productImages as $index => $image)
              <img src="{{ asset('assets/admin/uploads/' . $image->photo) }}" alt="Thumbnail {{ $index + 1 }}" class="thumbnail {{ $index == 0 ? 'active-thumb' : '' }}" onclick="changeMainImage(this)">
            @endforeach
          </div>
        @else
          <img src="{{ asset('assets/images/placeholder.png') }}" alt="No image available" class="main-product-image">
        @endif
      </div>
  
      <div class="product-info">
        <div class="product-title-price">
          <h2 class="product-name">{{ $locale == 'en' ? $product->name_en : $product->name_ar }}</h2>
          <span class="product-price">
            {{ $product->selling_price }} JD
            @if($product->offers->count() > 0)
              <span class="product-offer">{{ __('messages.On Sale') }}</span>
            @endif
          </span>
        </div>
  
        <p class="product-description">
          {{ $locale == 'en' ? $product->description_en : $product->description_ar }}
        </p>
  
        <div class="product-rating">
          <span>{{ __('messages.Rating') }}</span>
          <div class="stars">
            ★★★★☆
          </div>
        </div>
  
        <div class="product-action">
          <div class="quantity-selector">
            <div class="quantity-box">
              <button id="decreaseQuantity" onclick="updateQuantity(-1)">-</button>
              <span id="quantityValue">1</span>
              <button id="increaseQuantity" onclick="updateQuantity(1)">+</button>
            </div>              
          </div>
  
          <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">{{ __('messages.Add to Cart') }}</button>
        </div>
      </div>
    </div>
  </section>
  
  <section class="popular-section">
    <div class="popular-row">
      <div class="popular-bg-pattern"></div>
  
      <div class="container popular-content">
        <div class="popular-header">
          <h2 class="popular-title">{{ __('messages.Similar Food') }}</h2>
        </div>
  
        <div class="view-more-container">
          <a href="{{ route('menu') }}" class="view-more">{{ __('messages.View More') }}</a>
        </div>
  
        <!-- First row -->
        <div class="popular-grid-info">
          @foreach($similarProducts->take(4) as $similar)
            <div class="popular-card-info">
              @if($similar->productImages->count() > 0)
                <img src="{{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}" alt="{{ $locale == 'en' ? $similar->name_en : $similar->name_ar }}">
              @else
                <img src="{{ asset('assets/images/placeholder.png') }}" alt="No image available">
              @endif
              <h4>{{ $locale == 'en' ? $similar->name_en : $similar->name_ar }}</h4>
              <p class="price">{{ $similar->selling_price }} {{ __('messages.Currency') }}</p>
              <div class="card-footer">
                <div class="rating">⭐ 4.8</div>
                <button class="add-btn" onclick="quickAddToCart({{ $similar->id }})">+</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Second row (if there are more than 4 similar products) -->
    @if($similarProducts->count() > 4)
    <div class="popular-row">
      <div class="popular-bg-pattern"></div> 
  
      <div class="container popular-content">
        <div class="popular-grid-info">
          @foreach($similarProducts->skip(4)->take(4) as $similar)
            <div class="popular-card-info">
              @if($similar->productImages->count() > 0)
                <img src="{{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}" alt="{{ $locale == 'en' ? $similar->name_en : $similar->name_ar }}">
              @else
                <img src="{{ asset('assets/images/placeholder.png') }}" alt="No image available">
              @endif
              <h4>{{ $locale == 'en' ? $similar->name_en : $similar->name_ar }}</h4>
              <p class="price">{{ $similar->selling_price }} {{ __('messages.Currency') }}</p>
              <div class="card-footer">
                <div class="rating">⭐ 4.8</div>
                <button class="add-btn" onclick="quickAddToCart({{ $similar->id }})">+</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </section>
@endsection

@push('scripts')
<script>
  // Function to change main product image when thumbnail is clicked
  function changeMainImage(element) {
    // Update main image src
    document.getElementById('mainProductImage').src = element.src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
      thumb.classList.remove('active-thumb');
    });
    element.classList.add('active-thumb');
  }
  
  // Function to update quantity
  function updateQuantity(change) {
    const quantityElement = document.getElementById('quantityValue');
    let quantity = parseInt(quantityElement.textContent);
    
    quantity += change;
    
    // Ensure quantity doesn't go below 1
    if (quantity < 1) quantity = 1;
    
    quantityElement.textContent = quantity;
  }
  
  // Function to add product to cart
  function addToCart(productId) {
     // Check if user is authenticated
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    if (!isAuthenticated) {
      // Redirect to login page if not authenticated
      window.location.href = '{{ route("user.login") }}';
      return;
    }

    const quantity = parseInt(document.getElementById('quantityValue').textContent);
    
    // AJAX call to add to cart endpoint
    fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: quantity
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success message or update cart count
        alert('{{ __("messages.Product added to cart") }}');
      } else {
        alert('{{ __("messages.Error adding product to cart") }}');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
  
  // Function to quickly add one item to cart from similar products
  function quickAddToCart(productId) {
     // Check if user is authenticated
     const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    if (!isAuthenticated) {
      // Redirect to login page if not authenticated
      window.location.href = '{{ route("user.login") }}';
      return;
    }
    // AJAX call to add to cart endpoint with quantity 1
    fetch('{{ route("cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: 1
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Show success message or update cart count
        alert('{{ __("messages.Product added to cart") }}');
      } else {
        alert('{{ __("messages.Error adding product to cart") }}');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  }
</script>
@endpush