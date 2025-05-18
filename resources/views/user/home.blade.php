@extends('layouts.user')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-slider">
            @foreach ($banners as $banner)
                <div class="hero-slide active">
                    <img src="{{ asset('assets/admin/uploads/' . $banner->photo) }}" alt="Slide 1" class="hero-image" />
                </div>
            @endforeach
            <button class="hero-prev"><i class="fa fa-chevron-left"></i></button>
            <button class="hero-next"><i class="fa fa-chevron-right"></i></button>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>


    <section class="popular-section">
        <div class="popular-row">

            <div class="popular-bg-pattern"></div>

            <div class="container popular-content">
                <div class="popular-header">
                    <h2 class="popular-title">{{ __('messages.Most popular') }}</h2>
                </div>

                <div class="view-more-container">
                    <a href="{{ route('menu') }}" class="view-more">{{ __('messages.View More') }}</a>
                </div>

                <!-- الصف الأول هنا -->
                <div class="popular-grid">
                    @foreach ($populars as $popular)
                        <a href="{{ route('product.details', $popular->id) }}">
                            <div class="popular-card">
                                <img src="{{ asset('assets/admin/uploads/' . $popular->productImages->first()->photo) }}"
                                    alt="{{ $locale === 'ar' ? $popular->name_ar : $popular->name_en }}" />
                                <h4>{{ $locale === 'ar' ? $popular->name_ar : $popular->name_en }}</h4>
                                <p class="price">{{ $popular->selling_price }}JD</p>
                                <div class="card-footer">
                                    <div class="rating">⭐ 4.8</div>

                                    <!-- Updated button with auth check -->
                                    @auth
                                        <button class="add-btn"
                                            onclick="addToCart({{ $popular->id }}, 1); return false;">+</button>
                                    @else
                                        <button class="add-btn" onclick="showLoginPrompt(); return false;">+</button>
                                    @endauth
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>


            </div>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>


    <section class="branches-section">
        <div class="branches-container">
            <div class="branches-title-row">
                <h2 class="branches-title">{{ __('messages.Our Branches') }}</h2>
            </div>

            <div class="branches-cards">
                @foreach ($branches as $branch)
                    <a href="#" class="branch-card">
                        <img src="{{ asset('assets/admin/uploads/' . $branch->photo) }}" alt="Al-Muqabalin">
                        <p>{{ $branch->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="hero-divider">
            <div class="line"></div>
        </div>
    </section>



    <section class="appetizer-hero">
        @foreach ($banners as $banner)
            <div class="hero-slide active">
                <img src="{{ asset('assets/admin/uploads/' . $banner->photo) }}" alt="Slide 1" class="hero-image" />
            </div>
        @endforeach

    </section>


    <div class="hero-divider">
        <div class="line"></div>
    </div>


    <section class="contact-section">
        <div class="contact-container">
            <h2 class="contact-title">{{ __('messages.Contact Us') }}</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class="contact-row">
                    <input type="text" name="name" placeholder="{{ __('messages.Name') }}" class="contact-input"
                        required />
                    <input type="email" name="email" placeholder="{{ __('messages.Email') }}" class="contact-input"
                        required />
                </div>

                <input type="text" name="subject" placeholder="{{ __('messages.Subject') }}" class="contact-input-full"
                    required />

                <textarea name="message" placeholder="{{ __('messages.Message') }}" class="contact-textarea" required></textarea>

                <button type="submit" class="contact-btn">{{ __('messages.Send') }}</button>
            </form>
        </div>
    </section>


    <div class="hero-divider">
        <div class="line"></div>
    </div>


    <section class="about-section">
        <div class="about-container">
            <h2 class="about-title">{{ __('messages.About Us') }}</h2>

            <p class="about-intro">{{ $locale === 'ar' ? $page->title_ar : $page->title_en }}</p>

            <p class="about-description">
                {!! $locale === 'ar' ? $page->content_ar : $page->content_en !!}
            </p>
        </div>
    </section>


    <section class="decorative-divider"></section>

    @push('scripts')
        <!-- Add this JavaScript to the bottom of your page or in a separate JS file -->
        <script>
            function addToCart(productId, quantity) {
                // Prevent default link behavior
                event.preventDefault();

                // Send AJAX request to add to cart
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            offer_id: null // Set this dynamically if needed
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert(data.message);
                            // Update cart count if needed
                            // updateCartCount(data.cart_count);
                        } else {
                            // Show error message
                            alert(data.message);
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                    });
            }

            function showLoginPrompt() {
                // Prevent default link behavior
                event.preventDefault();

                // Show login prompt
                alert('Please login to add items to cart');
                // Redirect to login page
                window.location.href = '{{ route('user.login') }}';
            }
        </script>
    @endpush
@endsection
