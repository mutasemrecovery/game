<header class="navbar">
  <div class="container">
    
    <div class="logo">
      <a href="{{ route('home') }}">
        <img src="{{ asset('assets_front/assets/images/logo.png') }}" alt="The Dynamite Box Logo" />
      </a>
    </div>

    <button class="navbar-toggle" id="navbar-toggle">
      <i class="fa fa-bars"></i>
    </button>

    <div class="nav-wrapper" id="nav-wrapper">
      <nav class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.Home') }}</a>

        <div class="menu-dropdown-container">
          <a href="#" class="menu-toggle-link">{{ __('messages.Menu') }} <i class="fa fa-chevron-down"></i></a>
          <div class="menu-dropdown-panel">
            <div class="menu-dropdown-grid">
              @php
              $products = App\Models\Product::limit(6)->get();    
              $locale = App::getLocale();
              @endphp
              @foreach ($products as $product)
              <!-- Menu items -->
              <a href="{{ route('product.details', $product->id) }}">
                <div class="menu-dropdown-item">
                  @if($product->productImages && $product->productImages->first())
                    <img src="{{ asset('assets/admin/uploads/' . $product->productImages->first()->photo) }}" alt="{{ $locale == 'en' ? $product->name_en : $product->name_ar }}">
                  @endif
                  <span>{{ $locale == 'en' ? $product->name_en : $product->name_ar }}</span>
                </div>
              </a>
              @endforeach
            </div>
            <div class="menu-dropdown-footer">
              <a href="{{ route('menu') }}" class="menu-view-full-btn">{{ __('messages.View Full Menu') }}</a>
            </div>
          </div>
        </div>

        <a href="" class="">{{ __('messages.Our Branches') }}</a>
        <a href="" class="">{{ __('messages.About') }}</a>
        <a href="" class="franchise-btn ">{{ __('messages.Franchise') }}</a>
      </nav>
    </div>

    <!-- Icons section -->
    <div class="icons">
      @if (Auth::check())
        <div class="user-menu-container">
          <i class="fa fa-user" id="user-toggle"></i>
          <div class="user-dropdown" id="user-dropdown">
            <div class="user-info">
              <img src="{{ asset('assets_front/assets/images/boy.png') }}" alt="User Profile" />
              <span>{{ Auth::user()->name }}</span>
            </div>
            <ul class="user-links">
              <li><a href="{{ route('profile') }}">{{ __('messages.My Account') }}</a></li>
              <li><a href="{{ route('orders') }}">{{ __('messages.My Orders') }}</a></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="logout-btn">{{ __('messages.Logout') }}</button>
                </form>
              </li>
            </ul>
          </div>
        </div>

        <div class="cart-container">
          <a href="{{ route('cart.index') }}">
            <i class="fa fa-shopping-cart cart-icon"></i>
            @php
              $cartCount = App\Models\Cart::where('user_id', Auth::id())->where('status', 0)->count();
            @endphp
            @if($cartCount > 0)
              <span class="cart-badge">{{ $cartCount }}</span>
            @endif
          </a>
          <div class="cart-dropdown">
            <p><strong>{{ __('messages.Cart') }}</strong></p>
            <hr />
            @php
              $cartItems = App\Models\Cart::where('user_id', Auth::id())
                            ->where('status', 0)
                            ->with(['product'])
                            ->limit(3)
                            ->get();
              $subtotal = $cartItems->sum('total_price_product');
            @endphp
            
            @forelse($cartItems as $item)
              <div class="cart-item">
                @if($item->product && $item->product->productImages && $item->product->productImages->first())
                  <img src="{{ asset('assets/admin/uploads/' . $item->product->productImages->first()->photo) }}" alt="{{ $item->product->name_en }}" />
                @else
                  <img src="{{ asset('assets_front/assets/images/placeholder.png') }}" alt="Product" />
                @endif
                <div class="item-info">
                  <p>{{ $locale == 'en' ? $item->product->name_en : $item->product->name_ar }}</p>
                  <span>x{{ $item->quantity }}</span>
                </div>
                <div class="item-price">{{ $item->price }} JD</div>
              </div>
            @empty
              <div class="empty-cart">
                <p>{{ __('messages.Your cart is empty') }}</p>
              </div>
            @endforelse
            
            @if(count($cartItems) > 0)
              <div class="cart-subtotal">
                <span>{{ __('messages.Subtotal') }}:</span>
                <strong>{{ $subtotal }} JD</strong>
              </div>
              <a href="{{ route('cart.index') }}" class="view-cart-btn">{{ __('messages.View Cart') }}</a>
            @endif
          </div>
        </div>
      @else
        <div class="auth-links">
          <a href="{{ route('login') }}"><strong>{{ __('messages.Login') }}</strong></a> / 
          <a href="{{ route('register') }}"><strong>{{ __('messages.Register') }}</strong></a>
        </div>
      @endif
      
      <div class="language-selector">
        <i class="fa fa-globe" id="lang-toggle"></i>
        <div class="lang-dropdown" id="lang-dropdown">
          <p>{{ __('messages.Language') }}</p>
          <hr />
          @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a class="nav-link {{ App::getLocale() == $localeCode ? 'active' : '' }}" 
               hreflang="{{ $localeCode }}" 
               href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
              <label>{{ $properties['native'] }}</label>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const navbarToggle = document.getElementById('navbar-toggle');
    const navWrapper = document.getElementById('nav-wrapper');
    
    navbarToggle.addEventListener('click', function() {
      navWrapper.classList.toggle('active');
    });
    
    // User dropdown toggle
    const userToggle = document.getElementById('user-toggle');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userToggle && userDropdown) {
      userToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
        
        // Hide language dropdown if open
        if (langDropdown && langDropdown.classList.contains('show')) {
          langDropdown.classList.remove('show');
        }
      });
    }
    
    // Language dropdown toggle
    const langToggle = document.getElementById('lang-toggle');
    const langDropdown = document.getElementById('lang-dropdown');
    
    if (langToggle && langDropdown) {
      langToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        langDropdown.classList.toggle('show');
        
        // Hide user dropdown if open
        if (userDropdown && userDropdown.classList.contains('show')) {
          userDropdown.classList.remove('show');
        }
      });
    }
    
    // Hide dropdowns when clicking elsewhere
    document.addEventListener('click', function() {
      if (userDropdown && userDropdown.classList.contains('show')) {
        userDropdown.classList.remove('show');
      }
      
      if (langDropdown && langDropdown.classList.contains('show')) {
        langDropdown.classList.remove('show');
      }
    });
    
    // Prevent dropdown from closing when clicking inside it
    if (userDropdown) {
      userDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
    
    if (langDropdown) {
      langDropdown.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
  });
</script>